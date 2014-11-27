<?php
class EquipmentController extends \BaseController {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $query = Input::has('query') ? Input::get('query') : '';
        $query = implode('%', explode(' ', $query));
        
        return Equipment::join('facilities', 'equipment.facility_id', '=', 'facilities.id')->
               select('equipment.*', 'facilities.name as facility', 'facilities.province')->
               where('equipment.name', 'LIKE', "%$query%")->
               orWhere('equipment.specifications', 'LIKE', "%$query%")->
               orWhere('equipment.purpose', 'LIKE', "%$query%")->get();
    }
}