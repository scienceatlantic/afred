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

          if ($request->orderByField) {
              $formEntries->orderBy($request->orderByField, 'asc');
          } else {
              $formEntries->orderBy('order_by_title', 'asc');
          }

          $formEntries->groupBy('resource_id');

          return [ 'result' => $this->pageOrGet($formEntries), 'metrics' => $this->getCurrentSearchMetrics($formEntries)];

        } else if ($request->searchFlag == "pending_edits") {

          $formEntries = $formEntries
              ->where('form_entry_status_id', 1)
              ->orderBy('updated_at', 'desc');

          return [ 'result' => $formEntries->paginate(5), 'metrics' => $this->getCurrentSearchMetrics($formEntries)];

        }


    }

    /**
    * Returns relevant metrics for current searches
    */
    public function getCurrentSearchMetrics($formEntryResults)
    {

      $metrics = [];
      $count = 0;
      $status_names = [];
      $results = $formEntryResults->paginate(1000);
      foreach($results as $formEntry){
        $count++;
        if(!array_key_exists($formEntry->form_entry_status_id, $status_names)) {
          $status = Status::findStatusById($formEntry->form_entry_status_id);
          $status_names[$status->id] = strtolower($status->name);
        };
        if(!array_key_exists($status_names[$formEntry->form_entry_status_id], $metrics)) {
          $metrics[$status_names[$formEntry->form_entry_status_id]] = 0;
        };
        $metrics[$status_names[$formEntry->form_entry_status_id]] += 1;
      }
      $metrics['count'] = $count;
      return $metrics;

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
