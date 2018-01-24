<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Form;
use Illuminate\Http\Request;

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
    public function index($directoryId)
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
    public function show($directoryId, $formId)
    {
        return Directory
            ::findOrFail($directoryId)
            ->forms()
            ->with(self::$withRelationships)
            ->findOrFail($formId);
    }
}
