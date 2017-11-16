<?php

namespace App\Http\Controllers;

use App\Resource;
use App\FormEntry;
use App\FormEntryStatus;
use App\FormField;
use App\DateValue;
use App\NumberValue;
use App\StringValue;
use App\TextValue;
use App\FormSection;
use Illuminate\Http\Request;
use View;

class ResourceController extends Controller
{
    public static $withRelationships = [
        
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resources = $this->pageOrGet(Resource::query());
        foreach($resources as &$resource) {
            $resource->format($request->status);
        }
        return $resources;
    }

    public function action(Request $request, $id = null)
    {
        switch (strtolower($request->action)) {
            case 'submit':
            case '':
                return $this->submitFormEntry($request);
            case 'publish':
                return $this->publishFormEntry($request, $id);
            case 'reject':
                return $this->rejectFormEntry($request, $id);
            case 'delete':
                return $this->deleteFormEntry($request, $id);
            default:
                abort(404);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $e = Resource::findOrFail($id);
        $e->format($request->status);
        if ($request->templateId) {
            return View
                ::make('templates.published-resources.id-1', $e->formatted)
                ->render();
        }
        return $e;
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

    function submitFormEntry($request)
    {
        // Create new resource.
        $resource = new Resource();
        $resource->save();

        // Get the status.
        $formEntryStatus = FormEntryStatus
            ::where('name', 'Submitted')
            ->first();

        // Create new form entry.
        $formEntry = new FormEntry();
        $formEntry->form_entry_status_id = $formEntryStatus->id;
        $formEntry->resource_id = $resource->id;
        $formEntry->save();

        self::storeFormEntry($request, $formEntry);

        return Resource::with(self::$withRelationships)->find($resource->id);
    }

    private function publishFormEntry(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $submittedStatus = FormEntryStatus
            ::where('name', 'Submitted')
            ->first();

        $publishedStatus = FormEntryStatus
            ::where('name', 'Published')
            ->first();

        $formEntry = $resource->formEntries()
            ->where('form_entry_status_id', $submittedStatus->id)
            ->first();
    
        $formEntry->form_entry_status_id = $publishedStatus->id;
        $formEntry->update();

        self::addFormEntryToWordpress();
        self::addFormEntryToSearch();

        // Email event

        return Resource::with(self::$withRelationships)->find($resource->id);
    }

    private function rejectFormEntry()
    {

    }

    private function deleteFormEntry()
    {
        
    }

    private static function storeFormEntry(
        Request $request,
        FormEntry $formEntry
    ) {
        foreach($request->all() as $section => $fieldsets) {
            $formSection = FormSection
                ::where('object_key', $section)
                ->first();

            // Ignore if it's not a form section.
            if (!$formSection) {
                continue;
            }

            $sectRepeatIndex = 0;
            foreach($fieldsets as $fieldset) {
                $sectRepeatIndex++;

                foreach($formSection->fields as $formField) {
                    if (isset($fieldset[$formField->object_key])) {
                        $value = $fieldset[$formField->object_key];
                    } else {
                        continue;
                    }

                    $valueModelClass = 'App\\';
                    switch ($formField->type->name) {
                        case 'richtext':
                        case 'plaintext':
                            $valueModelClass .= 'Text';
                            break;
                        case 'radio':
                        case 'checkbox':
                        case 'dropdown':
                            $valueModelClass .= 'Labelled';
                            break;
                        default:
                            $valueModelClass .= ucfirst($formField->type->name);
                    }
                    $valueModelClass .= 'Value';
                    
                    switch ($formField->type->name) {
                        case 'string':
                        case 'richtext':
                        case 'plaintext':
                        case 'number':
                            $v = new $valueModelClass();
                            $v->form_field_id = $formField->id;
                            $v->form_entry_id = $formEntry->id;
                            $v->section_repeat_index = $sectRepeatIndex;
                            $v->value = $value;
                            $v->save();
                            break;
                        case 'checkbox':
                            foreach($value as $id) {
                                $v = $valueModelClass::find($id);
                                $v->formEntries()->attach($formEntry->id, [
                                    'section_repeat_index' => $sectRepeatIndex
                                ]);
                            }
                            break;
                        case 'radio':
                        case 'dropdown':
                            $v = $valueModelClass::find($value);
                            $v->formEntries()->attach($formEntry->id, [
                                'section_repeat_index' => $sectRepeatIndex
                            ]);
                            break;
                    }
                }
            }
        }
    }

    private static function addFormEntryToWordpress()
    {

    }

    private static function addFormEntryToSearch()
    {

    }
}
