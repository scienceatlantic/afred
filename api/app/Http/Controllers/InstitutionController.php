<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Institution;
use App\Http\Requests;
use App\Http\Requests\IndexInstitutionRequest;
use App\Http\Controllers\Controller;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexInstitutionRequest $request)
    {
        $paginate = $request->input('paginate', true);
        $itemsPerPage = $request->input('itemsPerPage', 15);
        $institution = Institution::where('isHidden', false);
        $institution = $paginate ?
            $institution->paginate($itemsPerPage) : $institution->get();
        $this->_expandRelationships($request, $institution, true);
        return $institution;
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
    public function show(Request $request, $id)
    {
        $institution = Institution::findOrFail($id);
        $this->_expandRelationships($request, $institution);
        return $institution;
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
        return Institution::destroy($id);
    }
}
