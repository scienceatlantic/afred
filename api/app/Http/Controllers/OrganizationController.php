<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Models.
use App\Organization;

// Requests.
use App\Http\Requests;
use App\Http\Requests\OrganizationRequest;

class OrganizationController extends Controller
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
    public function index(OrganizationRequest $request)
    {
        $o = Organization::with('ilo');
        
        if ($request->has('isHidden')) {
            if ($request->input('isHidden', 0)) {
                $o->hidden();
            } else {
                $o->notHidden();
            }
        }
        
        $o->orderBy('name', 'asc');
        
        return $this->pageOrGet($o);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationRequest $request)
    {
        // Get current datetime.
        $now = $this->now();
        
        $o = new Organization();
        $o->name = $request->name;
        $o->isHidden = $request->isHidden;
        $o->dateCreated = $now;
        $o->dateUpdated = $now;
        $o->save();
        return $this->toCcArray($o->toArray());
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrganizationRequest $request, $id)
    {
        $o = Organization::with('ilo')->findOrFail($id);
        $o->name = $request->name;
        $o->isHidden = $request->isHidden;
        $o->dateUpdated = $this->now();
        $o->save();        
        return $this->toCcArray($o->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OrganizationRequest $request, $id)
    {
        $o = Organization::with('ilo')->findOrFail($id)->toArray();
        return $this->toCcArray($o);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrganizationRequest $request, $id)
    {
        $o = Organization::findOrFail($id);
        $deletedOrg = $this->toCcArray($o->toArray());
        $o->delete();
        return $deletedOrg;
    }
}
