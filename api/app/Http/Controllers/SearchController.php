<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Log;

// Models.
use App\Equipment;
use App\Facility;

// Requests.
use App\Http\Requests;

class SearchController extends Controller
{
    function __construct(Request $request)
    {
        parent::__construct($request);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Process the query.
        $q = $this->_processQuery($request->input('q'));
        
        Log::debug($q);
        
        // Get the type of search (ie. 'facility' or 'equipment').
        if (($type = strtolower($request->input('type'))) != 'facility') {
            $type = 'equipment';
        }
        
        // Load required relationships.
        if ($type == 'facility') {
            $results = Facility::with('province');
        } else {
            $results = Equipment::with('facility.province');
        }
        
        // Search.
        if ($type == 'facility' && $q) {
            $results->search($q, [
                'name'                      => 30,
                'city'                      => 11,
                'description'               => 25,
                'website'                   => 15,
                
                'primaryContact.firstName'  => 10,
                'primaryContact.lastName'   => 10,
                'primaryContact.email'      => 10,
                
                'contacts.firstName'        => 10,
                'contacts.lastName'         => 10,
                'contacts.email'            => 10,
                
                'equipment.type'            => 25,
                'equipment.manufacturer'    => 20,
                'equipment.model'           => 20,
                'equipment.purpose'         => 20,
                'equipment.specifications'  => 20
            ]);
        } else if ($type == 'equipment' && $q) {
            $results->search($q, [
                'type'                      => 30,
                'manufacturer'              => 20,
                'model'                     => 15,
                'purpose'                   => 25,
                'specifications'            => 20,
                
                'facility.name'             => 25,
                'facility.city'             => 1,
                'facility.description'      => 20,
                'facility.website'          => 5,
            ]);
        }        
        
        // Advanced search.
        if (is_array(($ids = $request->input('provinceId')))) {
            if ($type == 'facility') {
                $results->whereHas('province', function($query) use ($ids)
                {
                    $query->whereIn('provinceId', $ids);
                });                   
            } else if ($type == 'equipment') {
                $results->whereHas('facility', function($query) use ($ids)
                {
                    $query->whereIn('facilities.provinceId', $ids);
                });    
            }
        }
        
        if (is_array(($ids = $request->input('organizationId')))) {
            if ($type == 'facility') {
                $results->whereHas('organization', function($query) use ($ids)
                {
                    $query->whereIn('organizationId', $ids);
                });               
            } else if ($type == 'equipment') {
                $results->whereHas('facility', function($query) use ($ids)
                {
                    $query->whereIn('facilities.organizationId', $ids);
                });                 
            }

        }    
       
        if (is_array(($ids = $request->input('disciplineId')))) {
            if ($type == 'facility') {
                $results->whereHas('disciplines', function($query) use ($ids)
                {
                    $query->whereIn('disciplines.id', $ids);
                });               
            } else if ($type == 'equipment') {
                $results->whereHas('facility', function($query) use ($ids)
                {
                    $query->join('discipline_facility',
                                 'facilities.id',
                                 '=',
                                 'discipline_facility.facilityId')
                          ->whereIn('discipline_facility.disciplineId', $ids);
                });                
            }
        }
        
        if (is_array(($ids = $request->input('sectorId')))) {
            if ($type == 'facility') {
                $results->whereHas('sectors', function($query) use ($ids)
                {
                    $query->whereIn('sectors.id', $ids);
                });
            } else if ($type == 'equipment') {
                $results->whereHas('facility', function($query) use ($ids)
                {
                    $query->join('facility_sector',
                                 'facilities.id',
                                 '=',
                                 'facility_sector.sectorId')
                          ->whereIn('facility_sector.sectorId', $ids);
                });          
            }
        }
        
        return $results->paginate(15); 
    }
    
    private function _processQuery($q)
    {
        if (!($q = trim($q))) {
            return '';
        }
        
        return '%' . implode("%", explode(' ', $q)) . '%';
    }
}
