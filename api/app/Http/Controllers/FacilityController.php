<?php

namespace App\Http\Controllers;

// Misc.
use Log;

// Models.
use App\Facility;
use App\Contact;
use App\PrimaryContact;

// Requests.
use Illuminate\Http\Request;
use App\Http\Requests\FacilityRequest;

class FacilityController extends Controller
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
    public function index(FacilityRequest $request)
    {
        $f = Facility::with([
            'province',
            'organization.ilo',
            'disciplines',
            'sectors',
            'primaryContact',
            'contacts',
            'equipment' => function($query) {
                $query->notHidden();
            }
        ])->notHidden();
        return $this->pageOrGet($f);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FacilityRequest $request, $id)
    {
        $f = Facility::with([
            'province',
            'organization.ilo',
            'disciplines',
            'sectors',
            'primaryContact',
            'contacts',
            'equipment' => function($query) {
                $query->notHidden();
            }          
        ])->notHidden()->findOrFail($id);
        
        // If an equipment ID is provided with the request, check that the
        // equipment exists and it's not hidden.
        if ($eId = $request->equipmentId) {
            $f->equipment()->notHidden()->findOrFail($eId);
        }
                
        return $this->toCcArray($f->toArray());   
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FacilityRequest $request, $id)
    {
        // We can on update a facility record by marking it as private or
        // public.
        $f = Facility::findOrFail($id);
        $f->isPublic = $request->isPublic;

        // Update without updating search index.
        Facility::withoutSyncingToSearch(function() use (&$f) {
            $f->update();
        });

        // Update/remove facility from search index depending on visibility. 
        if ($f->isPublic) {
            $f->searchable();
            $f->equipment()->searchable();
        } else {
            $f->unsearchable();
            $f->equipment()->unsearchable();
        }

        return $this->toCcArray($f->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacilityRequest $request, $id)
    {
        $f = Facility::findOrFail($id);
        $deletedFacility = $this->toCcArray($f->toArray());

        // Remove all associated equipment from index. We do not have to do this 
        // explicitly for the facility because that is done automatically when
        // we run `$f->delete()`.
        $f->equipment()->unsearchable();

        $f->delete();
        return $deletedFacility;
    }
}
