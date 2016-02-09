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
        
        // Unless we're dealing with a 'PENDING_APPROVAL' record, grab
        // the existing FacilityRepository object.
        if ($request->input('state') != 'PENDING_APPROVAL') {
           $fr = FacilityRepository::findOrFail($id); 
        } else {
            $fr = new FacilityRepository();
        }
        
        // Update the FacilityRepository's state.
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
                $fr->update();
                break;
            
            case 'REJECTED':
                $fr->update();
                break;
            
            case 'PENDING_EDIT_APPROVAL':
                // Make sure the facility repository record has an open token.
                $ful = $fr->tokens()->open()->firstOrFail();
                
                // We're actually creating a new facility repository record
                // here. We have to pass the old facility repository record
                // into the _formatFrData function because we need its link
                // to the facility before we can create a new one.
                $fr->data = $this->_formatFrData($request, $now, true, $fr);
                $fr->dateSubmitted = $now;
                $fr = new FacilityRepository($fr->toArray());
                $fr->save();
                
                // Update the token and mark it as pending.
                $ful->frIdAfter = $fr->id;
                $ful->status = 'PENDING';
                $ful->save();
                break;
            
            case 'PUBLISHED_EDIT':
                $data = $this->_publishFacility($fr, $fr->data, true);
                $fr->facilityId = $data['facility']['id'];
                $fr->data = $data;
                $fr->update();
                
                // Close the token.
                $ful = $fr->token()->pending()->first();
                $ful->status = 'CLOSED';
                $ful->update();
                break;
            
            case 'REJECTED_EDIT':
                $fr->update();
                
                // Close the token.
                $ful = $fr->token()->pending()->first();
                $ful->status = 'CLOSED';
                $ful->update();
                break;
        }
        
        event(new FacilityRepositoryEvent($fr));
        return $fr;
    }
        
    private function _formatFrData($request,
                                   $now,
                                   $isUpdate = false,
                                   $fr = null)
    {
        $data = [];
        
        // Storing all the request data into smaller more manageable variables.
        $fac = $request->data['facility'];
        $org = !$fac['organizationId'] ? $request->data['organization'] : null;
        $disciplines = $request->data['disciplines'];
        $sectors = $request->data['sectors'];
        $pContacts = $request->data['primaryContact'];
        $contacts = $request->data['contacts'];
        $equipment = $request->data['equipment'];
        
        // Organization section.
        // If an organizationId was provided, skip this part (meaning an
        // exising organization was selected). If an organizationId was not
        // provided, store the details (ie. name) in $data.
        if (!$fac['organizationId']) {
            $data['organization'] = (new Organization($org))->toArray();
            $data['organization']['isHidden'] = false;
            $data['organization']['dateAdded'] = $now;
        }

        // Facility section.
        $data['facility'] = (new Facility($fac))->toArray();
        $data['facility']['isPublic'] = true;
        $data['facility']['dateUpdated'] = $now;
        // This part is for updates.
        if ($isUpdate) {
            // Retrieve some data from the existing facility.
            $id = $fr->facility->id;
            $dateSubmitted = $fr->facility->dateSubmitted->toDateTimeString();
            
            // ID and date submitted are maintained.
            $data['facility']['id'] = $id;
            $data['facility']['dateSubmitted'] = $dateSubmitted;
        // For new records.
        } else {
            $data['facility']['dateSubmitted'] = $now;         
        }
        
        // Disciplines section.
        $data['disciplines'] = $disciplines;
        
        // Sectors section.
        $data['sectors'] = $sectors;
        
        // Primary contact section.
        $data['primaryContact'] = (new PrimaryContact($pContacts))->toArray();
        
        // Contacts section.
        foreach($contacts as $i => $c) {
            $data['contacts'][$i] = (new Contact($c))->toArray();
        }
        
        // Equipment section.
        foreach($equipment as $i => $e) {
            $data['equipment'][$i] = (new Equipment($e))->toArray();
        }
        
        return $data;
    }
    
    private function _publishFacility($fr, $data, $isUpdate = false)
    {        
        // Organization section.
        // If the organization key in $d exists, create the
        // organization and store its key into $d['facility'].
        if (array_key_exists('organization', $data)) {
            $orgId = Organization::create($data['organization'])->getKey();
            $data['facility']['organizationId'] = $orgId;
        }
        
        // Facility section.
        // For updates.
        if ($isUpdate) {
            // Before updating the facility, insert the new facility
            // repository id.
            $data['facility']['facilityRepositoryId'] = $fr->id;
            $f = Facility::find($data['facility']['id']);
            $f->update($data['facility']);
            $data['facility'] = $f->toArray();
            
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
            $f = $fr->facility()->create($data['facility']);
            $data['facility'] = $f->toArray();
        }
        
        // Disciplines section.
        $f->disciplines()->attach($data['disciplines']);
        
        // Sectors section.
        $f->sectors()->attach($data['sectors']);
        
        // Primary contact section.
        $data['primaryContact'] =
            $f->primaryContact()->create($data['primaryContact']);
        
        // Contacts section.
        // Contacts are optional, so we first have to check if it exists.
        if (array_key_exists('contacts', $data)) {
            foreach($data['contacts'] as $i => $c) {
                $data['contacts'][$i] = $f->contacts()->create($c)->toArray();          
            }                       
        }
        
        // Equipment section
        foreach($data['equipment'] as $i => $e) {
            $data['equipment'][$i] = $f->equipment()->create($e)->toArray();           
        }
        
        return $data;
    }
}
