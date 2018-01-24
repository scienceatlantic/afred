<?php

namespace App\Http\Controllers;

use App\Algolia;
use DB;
use App\Directory;
use App\EntryField;
use App\EntrySection;
use App\Form;
use App\FormEntry;
use App\FormEntryStatus as Status;
use App\FormSection;
use App\Wordpress;
use Illuminate\Http\Request;
use View;

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
            $formEntries = $formEntries->where(
                'form_entry_status_id',
                Status::findStatus($request->status)->id
            );
        }

        return $this->pageOrGet($formEntries);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $directoryId, $formId, $formEntryId)
    {
        $formEntry = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formEntries()
            ->findOrFail($formEntryId);

        if ($request->template) {
            $template = "templates.r.{$request->template}";
            if (View::exists($template)) {
                try {
                    return [
                        'html' => View::make($template, $formEntry)->render()
                    ];
                } catch (\Exception $e) {
                    // Log and go to abort command below.
                    Log::error($e->getMessage());
                }
            }
            abort(404);
        }
    
        return $formEntry;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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

        $action = $request->action;

        switch ($action) {
            case 'submit':
                return $this->submit($request, $form);
            case 'publish':
            case 'reject':
            case 'delete':
                $formEntry = $form->formEntries()->findOrFail($formEntryId);
                return $this->$action($formEntry);
            default:
                abort(404);
        }
    }

    function submit(Request $request, Form $form)
    {
        $formEntry = null;

        DB::transaction(function() use ($request, $form, $formEntry) {
            $formEntry = FormEntry::saveEntry($request, $form);
        });

        return $formEntry;
    }

    private function publish(FormEntry $formEntry)
    {
        DB::transaction(function() use ($formEntry) {
            $formEntry->updateStatus(Status::findStatus('Published')->id);
            
            Wordpress::saveResources($formEntry);
            Algolia::addObjects($formEntry);
    
            // Email
        });

        return $formEntry;
    }

    private function reject(FormEntry $formEntry)
    {
        DB::transaction(function() use ($formEntry) {
            $formEntry->updateStatus(Status::findStatus('Rejected')->id);
        });
        
        return $formEntry;
    }
}
