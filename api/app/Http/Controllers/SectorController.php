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
        $sectors = Sector::orderBy('name', 'asc');
        
        if ($this->_paginate) {
            $sectors = $sectors->paginate($this->_itemsPerPage);
        } else {
            $sectors = $sectors->get();
        }
        
        return $this->_toCamelCase($sectors->toArray());
    }
}
