<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Models.
use App\Province;

// Requests.
use App\Http\Requests;

class ProvinceController extends Controller
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
    public function index(Request $request)
    {
        $p = Province::query();
        
        if ($request->has('isHidden')) {
            if ($request->input('isHidden', 0)) {
                $p->hidden();
            } else {
                $p->notHidden();
            }
        }
        
        $p->orderBy('name', 'asc');
        
        return $this->pageOrGet($p);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $p = new Province();
        $p->name = $request->name;
        $p->isHidden = $request->isHidden;
        $p->dateAdded = $this->now();
        $p->save();
        return $this->toCcArray($p->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->toCcArray(Province::findOrFail($id)->toArray());
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
        $p = Province::findOrFail($id);
        $p->name = $request->name;
        $p->isHidden = $request->isHidden;
        $p->save();
        return $this->toCcArray($p->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $p = Province::findOrFail($id);
        $deletedProvince = $p->toArray();
        $p->delete();
        return $this->toCcArray($deletedProvince);
    }
}
