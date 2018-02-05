<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Form;
use App\Http\Requests\FormRequest;

class FormController extends Controller
{
    public static $withRelationships = [
        'compatibleForms.directory',
        'formSections.formFields.type',
        'formSections.formFields.labelledValues'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormRequest $request, $directoryId)
    {
        return $this->pageOrGet(
            Directory
                ::findOrFail($directoryId)
                ->forms()
                ->with(self::$withRelationships)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FormRequest $request, $directoryId, $formId)
    {
        return Directory
            ::findOrFail($directoryId)
            ->forms()
            ->with(self::$withRelationships)
            ->findOrFail($formId);
    }
}
