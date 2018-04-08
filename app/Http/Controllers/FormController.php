<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Form;
use App\Http\Requests\FormIndexRequest;
use App\Http\Requests\FormShowRequest;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormIndexRequest $request, $directoryId)
    {
        return $this->pageOrGet(
            Directory
                ::findOrFail($directoryId)
                ->forms()
                ->with([
                    'compatibleForms.directory',
                    'formSections' => function($query) {
                        $query->active()
                            ->orderBy('form_sections.placement_order');
                    },
                    'formSections.formFields' => function($query) {
                        $query->active()
                            ->orderBy('form_fields.placement_order');
                    },
                    'formSections.formFields.type',
                    'formSections.formFields.labelledValues' => function($query) {
                        $query->where('form_field_labelled_value.is_active', 1)
                            ->orderBy('form_field_labelled_value.placement_order');
                    },
                ])
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FormShowRequest $request, $directoryId, $formId)
    {
        return Directory
            ::findOrFail($directoryId)
            ->forms()
            ->with([
                'compatibleForms.directory',
                'formSections' => function($query) {
                    $query->active()
                        ->orderBy('form_sections.placement_order');
                },
                'formSections.formFields' => function($query) {
                    $query->active()
                        ->orderBy('form_fields.placement_order');
                },
                'formSections.formFields.type',
                'formSections.formFields.labelledValues' => function($query) {
                    $query->where('form_field_labelled_value.is_active', 1)
                        ->orderBy('form_field_labelled_value.placement_order');
                },
            ])
            ->findOrFail($formId);
    }
}
