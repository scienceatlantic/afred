<?php

namespace App\Http\Controllers;

// Models.
use App\Role;

// Requests.
use Illuminate\Http\Request;
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
