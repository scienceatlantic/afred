<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Http\Requests\DirectoryRequest;

class DirectoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DirectoryRequest $request)
    {
        return $this->pageOrGet(Directory::query());
    }
}
