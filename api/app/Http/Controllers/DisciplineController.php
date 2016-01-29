<?php

namespace App\Http\Controllers;

// Controllers
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Models.
use App\Discipline;

// Requests.
use App\Http\Requests;

class DisciplineController extends Controller
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
        $disciplines = Discipline::orderBy('name', 'asc');
        
        if ($this->_paginate) {
            $disciplines = $disciplines->paginate($this->_itemsPerPage);
        } else {
            $disciplines = $disciplines->get();
        }
        
        return $this->_toCamelCase($disciplines->toArray());
    }
}
