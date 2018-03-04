<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Http\Requests\DirectoryIndexRequest;

class DirectoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DirectoryIndexRequest $request)
    {
        return $this->pageOrGet(Directory::query());
    }
}
