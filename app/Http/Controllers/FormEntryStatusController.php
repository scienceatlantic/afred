<?php

namespace App\Http\Controllers;

use App\FormEntryStatus;
use Illuminate\Http\Request;

class FormEntryStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->pageOrGet(FormEntryStatus::query());
    }
}
