<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Events.
use App\Events\FacilityRepositoryEvent;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Auth;
use Log;

// Models.
use App\Contact;
use App\Discipline;
use App\Equipment;
use App\Facility;
use App\FacilityRepository;
use App\FacilityUpdateLink;
use App\Organization;
use App\PrimaryContact;
use App\Sector;

// Requests.
use App\Http\Requests\IndexFacilityRepositoryRequest;
use App\Http\Requests\ShowFacilityRepositoryRequest;
use App\Http\Requests\UpdateFacilityRepositoryRequest;

class FacilityRepositoryController extends Controller
{
    function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexFacilityRepositoryRequest $request)
    {
        $state = $request->input('state', '%');
        $stateOp = $state  == '%' ? 'like' : '=';
        
        $fr = FacilityRepository::where('state', $stateOp, $state)
            ->paginate($this->_itemsPerPage);
            
        return $this->_toCamelCase($fr->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowFacilityRepositoryRequest $request, $id)
    {
        return FacilityRepository::findOrFail($id);            
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFacilityRepositoryRequest $request, $id = null)
    { 
        // Grab the current datetime.
        $now = $this->_now();
        
        // Unless we're dealing with a 'PENDING_APPROVAL' record, grab the
        // existing FacilityRepository object.
        if ($request->input('state') != 'PENDING_APPROVAL') {
            $fr = FacilityRepository::find($id); 
        } else {
            $fr = new FacilityRepository();
        }
        
        // Set/Update the Facility Repository's state.
        $fr->state = $request->input('state');
        
        switch ($fr->state) {
            case 'PENDING_APPROVAL':
                $fr->data = $this->_formatFrData($request, $now);
                $fr->dateSubmitted = $now;
                $fr->save();
                break;
            
            case 'PUBLISHED':
                $fr->data = $this->_publishFacility($fr, $fr->data);
                $fr->facilityId = $fr->data['facility']['id'];
                $fr->userId = Auth::user()->id;
                $fr->reviewMessage = $request->input('reviewMessage', null);
                $fr->update();
                break;
            
            case 'REJECTED':
                $fr->userId = Auth::user()->id;
                $fr->reviewMessage = $request->input('reviewMessage', null);
                $fr->update();
                break;
            
            case 'PENDING_EDIT_APPROVAL':
                // Grab the facility update link record before we update the
                // facility repository record below.
                $ful = $fr->fulsB()->open()->first();
                
                // We're actually creating a new facility repository record
                // here. We have to pass the old facility repository record
                // into the _formatFrData function because we need its link
                // to the facility before we can create a new one.
                $fr->data = $this->_formatFrData($request, $now, true, $fr);
                $fr->dateSubmitted = $now;
                $fr = new FacilityRepository($fr->toArray());
                $fr->save();
                
                // Update the token with the facility repository's id
                // (frIdAfter) and mark it as pending (it's no longer open,
                // since we're waiting for the admin to review it).
                $ful->frIdAfter = $fr->id;
                $ful->status = 'PENDING';
                $ful->save();
                break;
            
            case 'PUBLISHED_EDIT':
                $data = $this->_publishFacility($fr, $fr->data, true);
                $fr->facilityId = $data['facility']['id'];
                $fr->userId = Auth::user()->id;
                $fr->reviewMessage = $request->input('reviewMessage', null);
                $fr->data = $data;
                $fr->update();
                
                // Admin has reviewed the record, close the token.
                $ful = $fr->fulA()->pending()->first();
                $ful->status = 'CLOSED';
                $ful->update();
                break;
            
            case 'REJECTED_EDIT':
                $fr->userId = Auth::user()->id;
                $fr->reviewMessage = $request->input('reviewMessage', null);
                $fr->update();
                
                // Like 'PUBLISHED_EDIT', the admin as reviewed the record,
                // close it.
                $ful = $fr->fulA()->pending()->first();
                $ful->status = 'CLOSED';
                $ful->update();
                break;
        }
        
        // Generate an event (emails might need to be sent out).
        event(new FacilityRepositoryEvent($fr));
        
        return $fr;
    }
        
    private function _formatFrData($r, $now, $isUpdate = false, $fr = null)
    {
        // Will hold all the data that will be returned by the function.
        $d = [];
        
        // Organization section.
        // If an organizationId was provided, skip this part (meaning an
        // exising organization was selected). If an organizationId was not
        // provided, store the details (ie. name) in $d.
        if (!$r->data['facility']['organizationId']) {
            $d['organization'] = (new Organization($r->data['organization']))
                ->toArray();
            $d['organization']['isHidden'] = false;
            $d['organization']['dateAdded'] = $now;
        }

        // Facility section.
        $d['facility'] = (new Facility($r->data['facility']))->toArray();
        $d['facility']['isPublic'] = true;
        $d['facility']['dateUpdated'] = $now;
        // This part is for updates.
        if ($isUpdate) {
            // ID and date submitted are maintained.
            $d['facility']['id'] = $fr->facility->id;
            $d['facility']['dateSubmitted'] = $fr->facility->dateSubmitted
                ->toDateTimeString();
        // For new records.
        } else {
            $d['facility']['dateSubmitted'] = $now;         
        }
        
        // Disciplines section.
        $d['disciplines'] = $r->data['disciplines'];
        
        // Sectors section.
        $d['sectors'] = $r->data['sectors'];
        
        // Primary contact section.
        $d['primaryContact'] = (new PrimaryContact($r->data['primaryContact']))
            ->toArray();
        
        // Contacts section.
        foreach($r->data['contacts'] as $i => $c) {
            $d['contacts'][$i] = (new Contact($c))->toArray();
        }
        
        // Equipment section.
        foreach($r->data['equipment'] as $i => $e) {
            $d['equipment'][$i] = (new Equipment($e))->toArray();
        }
        
        return $d;
    }
    
    private function _publishFacility($fr, $d, $isUpdate = false)
    {        
        // Organization section.
        // If the organization key in $d exists, create the
        // organization and store its key into $d['facility'].
        if (array_key_exists('organization', $d)) {
            $orgId = Organization::create($d['organization'])->getKey();
            $d['facility']['organizationId'] = $orgId;
        }
        
        // Facility section.
        // For updates.
        if ($isUpdate) {
            // Before updating the facility, insert the new facility
            // repository id.
            $d['facility']['facilityRepositoryId'] = $fr->id;
            $f = Facility::find($d['facility']['id']);
            $f->update($d['facility']);
            $d['facility'] = $f->toArray();
            
            // For updates, we're going to delete all existing disciplines,
            // sectors, contacts, primary contact, and equipment data.
            $f->disciplines()->detach();
            $f->sectors()->detach();
            $f->primaryContact()->delete();
            $f->contacts()->delete();
            $f->equipment()->delete();                
        // For new records.
        } else {
            // This line automatically inserts the facility repository's ID
            // into the newly created record.
            $f = $fr->facility()->create($d['facility']);
            $d['facility'] = $f->toArray();
        }
        
        // Disciplines section.
        $f->disciplines()->attach($d['disciplines']);
        
        // Sectors section.
        $f->sectors()->attach($d['sectors']);
        
        // Primary contact section.
        $d['primaryContact'] =
            $f->primaryContact()->create($d['primaryContact']);
        
        // Contacts section.
        // Contacts are optional, so we first have to check if it exists.
        if (array_key_exists('contacts', $d)) {
            foreach($d['contacts'] as $i => $c) {
                $d['contacts'][$i] = $f->contacts()->create($c)->toArray();          
            }                       
        }
        
        // Equipment section
        foreach($d['equipment'] as $i => $e) {
            $d['equipment'][$i] = $f->equipment()->create($e)->toArray();           
        }
        
        return $d;
    }
}
