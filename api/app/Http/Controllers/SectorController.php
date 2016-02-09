<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Requests
use App\Http\Requests;

// Models.
use App\Sector;

class SectorController extends Controller
{
    function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $s = Sector::orderBy('name', 'asc');
        $s = $this->_paginate ? $s->paginate($this->_itemsPerPage) : $s->get();
        $this->_expandModelRelationships($s, true);
        return $this->_toCamelCase($s->toArray());
    }
}
