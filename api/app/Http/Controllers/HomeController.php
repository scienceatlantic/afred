<?php

namespace App\Http\Controllers;

// Controllers
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Models.
use App\Equipment;

// Requests.
use App\Http\Requests;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $take = 4;
        return [
            'equipment' => Equipment::with('facility')->notHidden()
                ->orderByRaw('RAND()')->take($take)->get()
        ];
    }
}
