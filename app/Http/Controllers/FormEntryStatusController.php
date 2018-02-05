<?php

namespace App\Http\Controllers;

use App\FormEntryStatus;
use App\Http\Requests\FormEntryStatusRequest;

class FormEntryStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormEntryStatus $request)
    {
        return $this->pageOrGet(FormEntryStatus::query());
    }
}
