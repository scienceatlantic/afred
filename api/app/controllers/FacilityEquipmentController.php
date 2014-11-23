<?php

class FacilityEquipmentController extends \BaseController {
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return Response
     */
    public function index($id)
    {
        return Facility::find($id)->equipment;
    }

    /**
     * Display the specified resource.
     *
     * @param int $facilityId
     * @param int $equipmentId
     * @return Response
     */
    public function show($facilityId, $equipmentId)
    {
        return Facility::find($facilityId)->equipment->find($equipmentId);
    }
}