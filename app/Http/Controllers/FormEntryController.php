<?php

namespace App\Http\Controllers;

use DB;
use App\Directory;
use App\FormEntry;
use App\FormEntryStatus as Status;
use App\Http\Requests\FormEntryRequest;

class FormEntryController extends Controller
{
    public static $relationships = [
        'status',
        'form.directory',
        'author',
        'reviewer'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormEntryRequest $request, $directoryId, $formId)
    {
        $formEntries = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formEntries()
            ->with(self::$relationships);

        if ($request->resourceId) {
            $formEntries->where('resource_id', $request->resourceId);
        }

        if ($request->status) {
            // Abort if status not found.
            if (!($status = Status::findStatus($request->status))) {
                abort(400);
            }

            $formEntries = $formEntries
                ->where('form_entry_status_id', $status->id);
        }

        if ($request->orderByDesc) {
            $formEntries->orderBy($request->orderByDesc, 'desc');
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
        FormEntryRequest $request,
        $directoryId,
        $formId,
        $formEntryId
    ) {
        return Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formEntries()
            ->with(self::$relationships)
            ->findOrFail($formEntryId);
    }

    public function action(
        FormEntryRequest $request,
        $directoryId,
        $formId,
        $formEntryId = null
    ) {
        $form = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId);

        $formEntry = '';
        DB::transaction(
            function() use ($request, $form, $formEntryId, &$formEntry) {
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

                        $formEntry = FormEntry::submitFormEntry(
                            $request,
                            $form,
                            $oldFormEntry
                        );
                        break;
                    case 'publish':
                    case 'reject':
                    case 'delete':
                        $method = $action . 'FormEntry';
                        $formEntry = FormEntry::$method(
                            $request,
                            $form->formEntries()->findOrFail($formEntryId)
                        );
                        break;
                    default:
                        abort(400);
                }
            }
        );
        return FormEntry::with(self::$relationships)->find($formEntry->id);
    }
}
