<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Model.
use App\Ilo;

// Requests.
use App\Http\Requests;
use App\Http\Requests\IloRequest;

class IloController extends Controller
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
    public function index(IloRequest $request)
    {   
        return $this->pageOrGet(new Ilo());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IloRequest $request)
    {
        // Get current datetime.
        $now = $this->now();
        
        $ilo = new Ilo();
        $ilo->organizationId = $request->organizationId;
        $ilo->firstName = $request->firstName;
        $ilo->lastName = $request->lastName;
        $ilo->email = $request->email;
        $ilo->telephone = $request->telephone;
        $ilo->extension = $request->extension;
        $ilo->position = $request->position;
        $ilo->website = $request->website;
        $ilo->dateCreated = $now;
        $ilo->dateUpdated = $now;
        $ilo->save();
        return $ilo;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(IloRequest $request, $id)
    {
        $ilo = Ilo::findOrFail($id);
        return $this->toCcArray($ilo->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(IloRequest $request, $id)
    {
        $ilo = Ilo::findOrFail($id);
        $ilo->organizationId = $request->organizationId;
        $ilo->firstName = $request->firstName;
        $ilo->lastName = $request->lastName;
        $ilo->email = $request->email;
        $ilo->telephone = $request->telephone;
        $ilo->extension = $request->extension;
        $ilo->position = $request->position;
        $ilo->website = $request->website;
        $ilo->dateUpdated = $this->now();
        $ilo->update();

        // Update search index.
        if ($f = $ilo->organization->facilities()->get()) {
            $f->searchable();
        }

        return $ilo;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(IloRequest $request, $id)
    {
        $ilo = Ilo::findOrFail($id);
        $deletedIlo = $ilo->toArray();
        $ilo->delete();
        return $this->toCcArray($deletedIlo);
    }
}
