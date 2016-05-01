<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Requests
use App\Http\Requests;
use App\Http\Requests\SectorRequest;

// Models.
use App\Sector;

class SectorController extends Controller
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
    public function index(SectorRequest $request)
    {
        return $this->pageOrGet(Sector::orderBy('name', 'asc'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectorRequest $request)
    {
        // Get current datetime.
        $now = $this->now();
        
        $s = new Sector();
        $s->name = $request->name;
        $s->dateCreated = $now;
        $s->dateUpdated = $now;
        $s->save();
        return $this->toCcArray($s->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SectorRequest $request, $id)
    {
        return $this->toCcArray(Sector::findOrFail($id)->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SectorRequest $request, $id)
    {
        $s = Sector::findOrFail($id);
        $s->name = $request->name;
        $s->dateUpdated = $this->now();
        $s->save();
        return $this->toCcArray($s->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SectorRequest $request, $id)
    {
        $s = Sector::findOrFail($id);
        $deletedSector = $s->toArray();
        $s->delete();
        return $this->toCcArray($deletedSector);
    }
}
