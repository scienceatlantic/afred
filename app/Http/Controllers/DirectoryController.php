<?php

namespace App\Http\Controllers;

use App\Directory;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->pageOrGet(Directory::query());
    }
}
