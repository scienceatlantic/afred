<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use App\Equipment;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = $request->input('q', '');
        $q = implode('%', explode(' ', $q));
        
        return Equipment::join('facilities', 'equipment.facility_id', '=',
            'facilities.id')->
            join('provinces', 'facilities.province_id', '=',
            'provinces.id')->
            select('equipment.*', 'facilities.name as facility',
            'facilities.city as city', 'provinces.name as province')->
            where('equipment.type', 'LIKE', "%$q%")->
            orWhere('equipment.manufacturer', 'LIKE', "%$q%")->
            orWhere('equipment.model', 'LIKE', "%$q%")->
            orWhere('equipment.specifications', 'LIKE', "%$q%")->
            orWhere('equipment.purpose', 'LIKE', "%$q%")->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $equipment = Equipment::find($id);
        
        if ($equipment) {
            if ($request->has('expand')) {
                foreach(explode(',', $request->input('expand')) as
                    $relation) {
                        $nestedRelation = explode('.', $relation);
                        if (count($nestedRelation) > 1) {
                            $equipment->$nestedRelation[0]->$nestedRelation[1];
                        }
                        else {
                            $equipment->$relation;
                        }
                }
            }
            return $equipment;         
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
