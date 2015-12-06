<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected function _expandRelationships($request, $models, $isArray = false)
    {
        if ($request->has('expand')) {
            foreach (explode(',',
                $request->input('expand')) as $relationship) {
                    if ($isArray) {
                        foreach($models as $model) {
                            $model->$relationship;
                        }                       
                    } else {
                        $models->$relationship;
                    }
            }
        }         
    }
}
