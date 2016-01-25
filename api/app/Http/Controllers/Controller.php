<?php

namespace App\Http\Controllers;

// Laravel.
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Misc.
use Carbon\Carbon;
use Log;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected function _expandModelRelationships($request,
                                                 $model,
                                                 $isCollection = false)
    {
        if ($request->has('expand')) {
            $relationships = explode(',', $request->input('expand'));
                                     
            foreach ($relationships as $relationship) {
                if ($isCollection) {
                    foreach($model as $m) {
                        $m->$relationship;
                    }                       
                } else {
                    $model->$relationship;
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
