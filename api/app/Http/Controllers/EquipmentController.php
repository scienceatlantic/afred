<?php

namespace Afred\Http\Controllers;

use Illuminate\Http\Request;

use Afred\Equipment;
use Afred\Http\Requests;
use Afred\Http\Controllers\Controller;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $query = $request->input('query', '');
        $query = implode('%', explode(' ', $query));
        
        return Equipment::join('facilities', 'equipment.facility_id', '=',
                'facilities.id')->
            select('equipment.*', 'facilities.name as facility',
                'facilities.province')->
            where('equipment.name', 'LIKE', "%$query%")->
            orWhere('equipment.specifications', 'LIKE', "%$query%")->
            orWhere('equipment.purpose', 'LIKE', "%$query%")->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
