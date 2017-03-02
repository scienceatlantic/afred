<?php

namespace App\Http\Controllers;

// Laravel.
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Misc.
use Auth;
use Carbon\Carbon;
use Log;
use Schema;

// Requests.
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * To paginate flag.
     * @type {boolean} 
     */
    protected $paginate;
    
    /**
     * Items per page (pagination).
     * @type {int}
     */
    protected $itemsPerPage;
    
    /**
     * Note: Deprecate this?
     * @type {string}
     */
    protected $expand;
    
    /**
     * Columns to perform an 'order by' operation on the database query in
     * ascending fashion.
     * @type {array}
     */
    protected $orderByAsc;
    
    /**
     * Columns to perform an 'order by' operation on the databse query in
     * ascending fashion.
     * @type {array}
     */
    protected $orderByDesc;
    
    function __construct(Request $request)
    {
        // To paginate or not. Default is to paginate.
        $this->paginate = boolval($request->input('paginate', true));
        
        // Number of items per page. Default is 15 items.
        $this->itemsPerPage = intval($request->input('itemsPerPage', 15));
        
        // Grab (if available) relationships to expand. (Deprecate?)
        $this->expand = explode(',', $request->input('expand', null));
        
        // Grab and parse (if available) items to order by (ascending). It
        // expects a string of comma separated values.
        $this->orderByAsc = explode(',', $request->input('orderByAsc', ""));
        
        // Grab and parse (if available) items to order by (descending). It
        // expects a string of comma separated values.
        $this->orderByDesc = explode(',', $request->input('orderByDesc', ""));
    }
    
    /**
     * Database 'order by' operation.
     * @param {string} $table Name of table the operation should be performed
     *     on.
     * @param {Eloquent model} $model Model the operation will be performed on.
     */
    protected function orderBy($table, $model)
    {
        foreach($this->orderByAsc as $column) {
            if (Schema::hasColumn($table, $column)) {
                $model->orderBy($column, 'asc');
            }
        }
        
        foreach($this->orderByDesc as $column) {
            if (Schema::hasColumn($table, $column)) {
                $model->orderBy($column, 'desc');
            }
        }      
    }
    
    /**
     * Returns an associative array whose keys have been converted to camel
     * case.
     * @param {array} $array Associative array.
     * @return {array}
     */
    public static function toCcArray($array)
    { 
        $a = [];
        foreach($array as $k => $v) {
            $a[camel_case($k)] = is_array($v) ? self::toCcArray($v) : $v;
        }
        return $a;
    }
    
    /**
     * Returns an Eloquent model after a paginate() or get() method is applied
     * to the model.
     * @param {Eloquent model} $model
     * @param {bool=true} $toCcArray  If set to true, a camel-cased array is 
     *     returned. Otherwise the Eloquent model is returned.
     * @param {string=null} $orderBy If set to the model's table name, it will
     *     will call the `orderBy()` method.
     */
    protected function pageOrGet($model, $toCcArray = true, $orderBy = null)
    {
        if ($orderBy) {
            $this->orderBy($orderBy, $model);
        }

        if ($this->paginate) {
            $model = $model->paginate($this->itemsPerPage);
        } else {
            $model = $model->get();
        }
        return $toCcArray ? $this->toCcArray($model->toArray()) : $model;
    }
    
    /**
     * Returns the current datetime.
     * @return {string} Current datetime.
     */
    public static function now($toDateTimeString = true)
    {
        $now = Carbon::now();
        return $toDateTimeString ? $now->toDateTimeString() : $now;
    }

    protected function isSuperAdmin($strict = false)
    {
        return Auth::check() && Auth::user()->isSuperAdmin($strict);
    }
    
    protected function isAdmin($strict = false)
    {
        return Auth::check() && Auth::user()->isAdmin($strict);
    }
}
