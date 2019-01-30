<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\Directory;
use App\FormEntry;
use App\FormEntryStatus as Status;
use App\Http\Requests\FormEntryRequest;
use App\Http\Requests\FormEntryActionRequest;
use App\Http\Requests\FormEntryIndexRequest;
use App\Http\Requests\FormEntryShowRequest;

class FormEntryController extends Controller
{
    public static $relationships = [
        'status',
        'form.directory',
        'formsAttachedTo.directory',
        'author',
        'reviewer',
        'primaryContact'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        FormEntryIndexRequest $request,
        $directoryId,
        $formId
    ) {
        Log::debug("====================");
        Log::debug("GETTING FORM ENTRIES");
        Log::debug($directoryId);
        Log::debug($formId);
        Log::debug($request->status);
        Log::debug($request->resourceId);
        Log::debug($request->keyword);
        Log::debug($request->searchFlag);
        Log::debug(print_r($request->query, true));
        $formEntries = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formEntries()
            ->with(self::$relationships);

        if ($request->resourceId) {
            $formEntries->where('resource_id', $request->resourceId);
        }

        if (!$request->searchFlag) {

          if ($request->status) {
              if ($request->status == "ANY"){
                  //We don't currently need to do anything special here.

              } else if (!($status = Status::findStatus($request->status))) {
                  // Abort if status not found.
                  abort(400);
              } else {

                $formEntries = $formEntries
                    ->where('form_entry_status_id', $status->id);

                // If requesting for rejected form entries, don't include edits.
                if ($status->name === 'Rejected') {
                    $formEntries = $formEntries
                        ->where('is_edit', false);
                }

              }
          }

          if ($request->keyword) {
            $formEntries->where('order_by_title', 'like', '%' . $request->keyword . '%');
          }

          if ($request->orderByDesc) {
              $formEntries->orderBy($request->orderByDesc, 'desc');
          } else {
              $formEntries->orderBy('order_by_title', 'asc');
          }

          return $this->pageOrGet($formEntries);

        } else if ($request->searchFlag == "new_submissions") {

          $formEntries = $formEntries
              ->where('is_edit', false)
              ->where('form_entry_status_id', 1)
              ->orderBy('updated_at', 'desc');
              
          return $formEntries->paginate(5);

        } else if ($request->searchFlag == "recent_edits") {

          $formEntries = $formEntries
              ->where('is_edit', true)
              ->where('form_entry_status_id', 4)
              ->orderBy('updated_at', 'desc');

          return $formEntries->paginate(5);

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        FormEntryShowRequest $request,
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
        FormEntryActionRequest $request,
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
                        $method = $action . 'FormEntry';
                        $formEntry = FormEntry::$method(
                            $request,
                            $form->formEntries()->findOrFail($formEntryId)
                        );
                        break;
                    case 'delete':
                    case 'hide':
                    case 'unhide':
                        $method = $action . 'FormEntry';
                        $formEntry = FormEntry::$method(
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
