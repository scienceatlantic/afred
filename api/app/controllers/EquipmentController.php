<?php
class EquipmentController extends \BaseController {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Equipment::all();
    }
}