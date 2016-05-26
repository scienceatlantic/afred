<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Log;

// Models.
use App\Facility;
use App\Contact;
use App\PrimaryContact;

// Requests.
use App\Http\Requests;
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
        if (!($email = $request->email)) {
            $f = Facility::with('province',
                                'organization',
                                'disciplines',
                                'sectors',
                                'primaryContact',
                                'contacts',
                                'equipment');
            return $this->pageOrGet($f);            
        }
        return $this->indexMatchingFacilities($email);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FacilityRequest $request, $id)
    {
        // If an equipment ID is also provided, check that the facility has
        // that piece of equipment.
        if ($equipmentId = $request->equipmentId) {
            Facility::find($id)->equipment()->findOrFail($equipmentId);
        }
        
        $f = Facility::with('province',
                            'organization',
                            'disciplines',
                            'sectors',
                            'primaryContact',
                            'contacts',
                            'equipment')->findOrFail($id)->toArray();        
        return $this->toCcArray($f);   
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
        // We can on update a facility record my marking it as private or
        // public.
        $f = Facility::findOrFail($id);
        $f->isPublic = $request->isPublic;
        $f->update();
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

        // Not allowed to delete a facility if it has an open/pending update
        // request.
        if ($f->currentRevision()->first()->fulB()->notClosed()->first()) {
            abort(400);
        }
        
        $deletedFacility = $this->toCcArray($f->toArray());
        $f->delete();
        return $deletedFacility;
    }
    
    private function indexMatchingFacilities($email)
    {
        // Find all matching contacts and grab their facility IDs.
        $cF = Contact::where('email', $email)->select('facilityId');
        
        // Find all matching primary contacts and grab their facility IDs.
        $pcF = PrimaryContact::where('email', $email)->select('facilityId');
        
        // Add the two results together and grab all the matching facilities.
        $ids = $cF->union($pcF)->get()->toArray();
        
        $f = Facility::leftJoin('facility_update_links',
                                'facility_update_links.frIdBefore', '=',
                                'facilities.facilityRepositoryId')
            ->whereIn('facilities.id', $ids)
            ->where('facility_update_links.status', '!=', 'CLOSED')
            ->select('facilities.id',
                     'facilities.name',
                     'facilities.city',
                     'facility_update_links.editorFirstName',
                     'facility_update_links.editorLastName',
                     'facility_update_links.editorEmail',
                     'facility_update_links.status');
        
        return $this->pageOrGet($f);
    }
}
