<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Models.
use App\Discipline;
use App\Equipment;
use App\Facility;
use App\FacilityRepository;
use App\Organization;
use App\Province;
use App\Sector;

// Requests.
use App\Http\Requests;
use App\Http\Requests\MiscRequest;

class MiscController extends Controller
{
    public function index(MiscRequest $request)
    {
        $item = $request->item;
        return $this->$item();
    }

    private function facilityRepositoryBreakdown()
    {
        return [
            'facilities' => [
                'published' => [
                    'total'   => FacilityRepository::published()->count(),
                    'public'  => FacilityRepository::published(true)->count(),
                    'private' => FacilityRepository::published(false)->count(),
                ],
                'pendingApproval'     => FacilityRepository::pendingApproval(false)->count(),
                'pendingEditApproval' => FacilityRepository::pendingEditApproval()->count(),
                'rejected'            => FacilityRepository::rejected(false)->count(),
                'deleted'             => FacilityRepository::removed()->get()->count()
            ],
            'equipment' => [
                'total'             => Equipment::count(),
                'public'            => Equipment::notHidden()->count(),
                'private'           => Equipment::hidden()->count(),
                'hasExcessCapacity' => Equipment::excessCapacity(true)->count(),
                'noExcessCapacity'  => Equipment::excessCapacity(false)->count()
            ]
        ];
    }

    private function randomEquipment()
    {
        $take = 4;
        $hiddenFacilities = Facility::hidden()->pluck('id');
        return [
            'equipment' => Equipment::with('facility')
                ->whereNotIn('facilityId', $hiddenFacilities)->notHidden()
                ->orderByRaw('RAND()')->take($take)->get()
        ];
    }

    private function searchFilters()
    {
        $d = [];
        $d['disciplines'] = Discipline::all();
        $d['sectors'] = Sector::all();
        $d['provinces'] = Province::notHidden()->get();
        $d['organizations'] = Organization::notHidden()->get();
        return $d;
    } 
}
