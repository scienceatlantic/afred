<?php
class FacilityContactController extends \BaseController {
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return Response
     */
    public function index($id)
    {
        return Facility::find($id)->contacts;
    }
}
