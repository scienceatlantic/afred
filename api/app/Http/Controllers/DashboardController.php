<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Models.
use App\Equipment as E;
use App\Facility as F;
use App\FacilityRepository as FR;

// Requests.
use App\Http\Requests;
use App\Http\Requests\DashboardRequest;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DashboardRequest $request)
    {   
        return [
            'facilities' => [
                'published' => [
                    'total'   => FR::published()->count(),
                    'public'  => FR::published(true)->count(),
                    'private' => FR::published(false)->count(),
                ],
                'pendingApproval'     => FR::pendingApproval(false)->count(),
                'pendingEditApproval' => FR::pendingEditApproval()->count(),
                'rejected'            => FR::rejected(false)->count(),
                'rejectedEdit'        => FR::rejected(true)->count(),
                'deleted'             => FR::removed()->count()
            ],
            'equipment' => [
                'total'             => E::count(),
                'public'            => E::notHidden()->count(),
                'private'           => E::hidden()->count(),
                'hasExcessCapacity' => E::excessCapacity(true)->count(),
                'noExcessCapacity'  => E::excessCapacity(false)->count()
            ]
        ];
    }
}
