<?php

namespace App\Http\Controllers;

// Controllers
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Models.
use App\Discipline;

// Requests.
use App\Http\Requests;
use App\Http\Requests\DisciplineRequest;

class DisciplineController extends Controller
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
    public function index(DisciplineRequest $request)
    {
        return $this->pageOrGet(Discipline::orderBy('name', 'asc'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DisciplineRequest $request)
    {
        // Get current datetime.
        $now = $this->now();
        
        $d = new Discipline();
        $d->name = $request->name;
        $d->dateCreated = $now;
        $d->dateUpdated = $now;
        $d->save();
        return $this->toCcArray($d->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DisciplineRequest $request, $id)
    {
        return $this->toCcArray(Discipline::findOrFail($id)->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DisciplineRequest $request, $id)
    {
        $d = Discipline::findOrFail($id);
        $d->name = $request->name;
        $d->dateUpdated = $this->now();
        $d->save();
        return $this->toCcArray($d->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DisciplineRequest $request, $id)
    {
        $d = Discipline::findOrFail($id);
        $deletedDiscipline = $d->toArray();
        $d->delete();
        return $this->toCcArray($deletedDiscipline);
    }
}
