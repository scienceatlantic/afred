<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CsrfController extends Controller
{
    public function show()
    {
        return csrf_token();
    }
}
