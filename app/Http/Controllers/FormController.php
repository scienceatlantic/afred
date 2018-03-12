<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Form;
use App\Http\Requests\FormIndexRequest;
use App\Http\Requests\FormShowRequest;

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
    public function index(FormIndexRequest $request, $directoryId)
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
    public function show(FormShowRequest $request, $directoryId, $formId)
    {
        return Directory
            ::findOrFail($directoryId)
            ->forms()
            ->with(self::$withRelationships)
            ->findOrFail($formId);
    }
}
