<?php

namespace App\Http\Controllers;

// Laravel.
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Schema;

// Misc.
use Carbon\Carbon;
use Log;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * To paginate flag.
     * @type {boolean} 
     */
    protected $_paginate;
    
    /**
     * Items per page (pagination).
     * @type {int}
     */
    protected $_ipp;
    
    /**
     * Note: Deprecate this?
     * @type {string}
     */
    protected $_expand;
    
    /**
     * Columns to perform an 'order by' operation on the database query in
     * ascending fashion.
     * @type {array}
     */
    protected $_orderByAsc;
    
    /**
     * Columns to perform an 'order by' operation on the databse query in
     * ascending fashion.
     * @type {array}
     */
    protected $_orderByDesc;
    
    function __construct(Request $request)
    {
        // To paginate or not. Default is to paginate.
        $this->_paginate = boolval($request->input('paginate', true));
        
        // Number of items per page. Default is 15 items.
        $this->_ipp = intval($request->input('itemsPerPage', 15));
        
        // Grab (if available) relationships to expand. (Deprecate?)
        $this->_expand = $request->input('expand', null);
        
        // Grab and parse (if available) items to order by (ascending). It
        // expects a string of comma separated values.
        $this->_orderByAsc = explode(',', $request->input('orderByAsc', ""));
        
        // Grab and parse (if available) items to order by (descending). It
        // expects a string of comma separated values.
        $this->_orderByDesc = explode(',', $request->input('orderByDesc', ""));
    }
    
    /**
     * Database order by operation.
     * @param {string} $table Name of table the operation should be performed
     *     on.
     * @param {Eloquent model} $model Model the operation will be performed on.
     */
    protected function _orderBy($table, $model)
    {        
        foreach($this->_orderByAsc as $column) {
            if (Schema::hasColumn($table, $column)) {
                $model->orderBy($column, 'asc');
            }
        }
        
        foreach($this->_orderByDesc as $column) {
            if (Schema::hasColumn($table, $column)) {
                $model->orderBy($column, 'desc');
            }
        }      
    }
    
    // Note: remove this?
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
    
    /**
     * Returns an associative array whose keys have been converted to camel
     * case.
     * 
     * @param {array} $array Associative array.
     * @return {array} An associative array whose keys are camel cased.
     */
    protected function _toCamelCase($array)
    { 
        $a = [];
        
        foreach($array as $k => $v) {
            $a[camel_case($k)] = is_array($v) ? $this->_toCamelCase($v) : $v;
        }
        
        return $a;
    }
    
    /**
     * Returns the current datetime.
     * @return {string} Current datetime.
     */
    public function _now()
    {
        return Carbon::now()->toDateTimeString();
    }
}
