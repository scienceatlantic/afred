<?php

namespace App\Http\Controllers;

use DB;
use App\Directory;
use App\FormEntry;
use App\FormEntryStatus as Status;
use Illuminate\Http\Request;

class FormEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $directoryId, $formId)
    {
        $formEntries = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formEntries();

        if ($request->status) {
            // Abort if status not found.
            if (!($status = Status::findStatus($request->status))) {
                abort(400);
            }

            $formEntries = $formEntries
                ->where('form_entry_status_id', $status->id);
        }

        return $this->pageOrGet($formEntries);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        Request $request,
        $directoryId,
        $formId,
        $formEntryId
    ) {
        return Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formEntries()
            ->findOrFail($formEntryId);
    }

    public function action(
        Request $request,
        $directoryId,
        $formId,
        $formEntryId = null
    ) {
        $form = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId);

        $response = null;
        DB::transaction(
            function() use ($request, $form, $formEntryId, &$response) {
                $action = $request->action;

                switch ($action) {
                    case 'submit':
                        // If the submission is an edit, look for the currently
                        // published form entry.
                        $oldFormEntry = null;
                        if ($formEntryId) {
                            $oldFormEntry = $form
                                ->formEntries()
                                ->findOrFail($formEntryId);
                        }

                        $response = FormEntry::submitFormEntry(
                            $request,
                            $form,
                            $oldFormEntry
                        );
                        break;
                    case 'publish':
                    case 'reject':
                    case 'delete':
                        $method = $action . 'FormEntry';
                        $response = FormEntry::$method(
                            $form->formEntries()->findOrFail($formEntryId)
                        );
                        break;
                    default:
                        abort(400);
                }
            }
        );
        return $response;
    }
}
