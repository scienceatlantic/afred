<?php

namespace App\Http\Controllers;

// Laravel.
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

// Misc.
use Carbon\Carbon;
use Log;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected $_paginate;
    protected $_itemsPerPage;
    protected $_expand;
    
    function __construct(Request $request) {
        $this->_paginate = boolval($request->input('paginate', true));
        $this->_itemsPerPage = intval($request->input('itemsPerPage', 15));
        $this->_expand = $request->input('expand', null);
    }
    
    protected function _expandModelRelationships($model, $isArray = false)
    {
        if ($this->_expand) {
            $relationships = explode(',', $this->_expand);
                                     
            foreach ($relationships as $relationship) {
                $nested = explode('.', $relationship);
                
                if (count($nested) > 1) {
                    if ($isArray) {
                        foreach($model as $m) {
                            $m->$nested[0]->$nested[1];
                        }                       
                    } else {
                        $model->$nested[0]->$nested[1];
                    }
                } else {
                    if ($isArray) {
                        foreach($model as $m) {
                            $m->$relationship;
                        }                       
                    } else {
                        $model->$relationship;
                    }
                }
            }
        }
    }
    
    protected function _toCamelCase($array)
    { 
        function camelCaseArrayKeys($array)
        {
            $newArray = [];
            
            foreach($array as $key => $value) {
                if (is_array($value)) {
                    $newArray[camel_case($key)] = camelCaseArrayKeys($value);
                } else {
                    $newArray[camel_case($key)] = $value;
                }
            }
            
            return $newArray;
        }

        return camelCaseArrayKeys($array);
    }
    
    public function _now()
    {
        return Carbon::now()->toDateTimeString();
    }
}
