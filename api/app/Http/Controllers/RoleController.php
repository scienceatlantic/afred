<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Models.
use App\Role;

// Requests.
use App\Http\Requests;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RoleRequest $request)
    {
        return $this->pageOrGet(Role::query());
    }
}
