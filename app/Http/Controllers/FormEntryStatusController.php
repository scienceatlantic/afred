<?php

namespace App\Http\Controllers;

use App\FormEntryStatus;
use App\Http\Requests\FormEntryStatusIndexRequest;

class FormEntryStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormEntryStatusIndexRequest $request)
    {
        return $this->pageOrGet(FormEntryStatus::query());
    }
}
