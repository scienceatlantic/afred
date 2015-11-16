<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Facility;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FacilityEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $facilityId
     * @return \Illuminate\Http\Response
     */
    public function index($facilityId)
    {
        return Facility::find($facilityId)->equipment;
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
     * @param  int  $facilityId
     * @param int  $equipmentId
     * @return \Illuminate\Http\Response
     */
    public function show($facilityId, $equipmentId)
    {
        return Facility::find($facilityId)->equipment->find($equipmentId);
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
