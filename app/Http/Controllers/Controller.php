<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Pagination flag.
     *
     * @type 
     */
    public $paginate;

    function __construct(Request $request)
    {
        $this->paginate = $request->input('paginate', true);
        $this->itemsPerPage = $request->input('itemsPerPage', 15);
    }
    
    public function pageOrGet($model)
    {
        if ($this->paginate) {
            return $model->paginate($this->itemsPerPage);
        }
        return $model->get();
    }    
}
