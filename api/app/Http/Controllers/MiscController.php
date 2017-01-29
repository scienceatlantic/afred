<?php

namespace App\Http\Controllers;

// Misc.
use \AlgoliaSearch\Client as Algolia;
use Log;

// Models.
use App\Discipline;
use App\Equipment;
use App\Facility;
use App\FacilityRepository;
use App\Organization;
use App\Province;
use App\Sector;

// Requests.
use Illuminate\Http\Request;
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
                'public'            => Equipment::notHidden(true)->count(),
                'private'           => Equipment::hidden(true)->count(),
                'hasExcessCapacity' => Equipment::excessCapacity(true)->count(),
                'noExcessCapacity'  => Equipment::excessCapacity(false)->count()
            ]
        ];
    }

    private function randomEquipment()
    {
        $take = 4;

        return [
            'equipment' => Equipment::with('facility')
                ->whereNotIn('facilityId', Facility::hidden()->pluck('id'))
                ->notHidden()
                ->whereRaw('LENGTH(type) > 4')
                ->whereRaw('LENGTH(purposeNoHtml) > 20')
                ->orderByRaw('RAND()')
                ->take($take)
                ->get()
        ];
    }

    private function searchFilters()
    {
        $d = [];
        $d['disciplines'] = Discipline::orderBy('name', 'asc')->get();
        $d['sectors'] = Sector::orderBy('name', 'asc')->get();
        $d['provinces'] = Province::notHidden()->orderBy('name', 'asc')->get();
        $d['organizations'] = Organization::notHidden()->orderBy('name', 'asc')
            ->get();
        return $d;
    }

    private function refreshSearchIndices()
    {
        // Save the original config value.
        $toQueue = config('scout.queue');

        // Turn off queueing.
        config(['scout.queue' => false]);

        // Initialise Algolia and clear indices
        try {
            $client = new Algolia(config('scout.algolia.id'), 
                config('scout.algolia.secret'));
            $client->initIndex(config('scout.prefix') . 'facilities')
                ->clearIndex();
            $client->initIndex(config('scout.prefix') . 'equipment')
                ->clearIndex();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }

        // Re-import data.
        Equipment::all()->searchable();
        Facility::all()->searchable();

        // Reset config value to original.
        config(['scout.queue' => $toQueue]);
    }

    private function searchIndices()
    {
        // Initialise Algolia and get all indices.
        try {
            $client = new Algolia(config('scout.algolia.id'), 
                config('scout.algolia.secret'));
            $allIndices = collect($client->listIndexes()['items'])
                ->keyBy('name');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }

        // Aliases.
        $facilities = config('scout.prefix') . 'facilities';
        $equipment = config('scout.prefix') . 'equipment';

        // Return matching facility and equipment indices only.
        $indices = [];
        $indices[$facilities] = $allIndices[$facilities];
        $indices[$equipment] = $allIndices[$equipment];
        
        return $indices;
    }
}
