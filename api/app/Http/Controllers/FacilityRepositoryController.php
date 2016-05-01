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
    function __construct(Request $request)
    {
        parent::__construct($request);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexFacilityRepositoryRequest $request)
    {        
        $fr = FacilityRepository::with('reviewer', 'facility');
        
        // Narrow down query by state.
        if (($state = $request->input('state'))) {
            if ($state == 'PENDING_APPROVAL'
                || $state == 'PENDING_EDIT_APPROVAL') {
                $fr->where('state', 'PENDING_APPROVAL')
                    ->orWhere('state', 'PENDING_EDIT_APPROVAL');
            }
            else if ($state == 'PUBLISHED' || $state == 'PUBLISHED_EDIT') {
                $visibility = (bool) $request->input('visibility', true);
                $frId = Facility::where('isPublic', $visibility)
                    ->select('facilityRepositoryId')->get();
                $fr->whereIn('id', $frId);                       
            }
            else if ($state == 'REJECTED' || $state == 'REJECTED_EDIT') {
                $fr->where('state', 'REJECTED')
                    ->orWhere('state', 'REJECTED_EDIT');
            }
            // This part needs work!
            else if ($state == 'DELETED') {
                $frId = Facility::select('facilityRepositoryId')->get();
                $fr->whereNotIn('id', $frId)
                    ->whereNotNull('facilityId');
            }
        }
        
        // Narrow down query by facility ID.
        if ($facilityId = $request->input('facilityId', null)) {
            $fr->where('facilityId', $facilityId);
        }
        
        return $this->pageOrGet($fr);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowFacilityRepositoryRequest $request, $id)
    {
        $fr = FacilityRepository::with([
            'fulA' => function($query) {
                $query->pending();
            },
            'reviewer',
            'facility'
        ])->findOrFail($id)->toArray();
        
        return $this->toCcArray($fr);
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
        $now = $this->now();
        
        // Grab the existing Facility Repository record (ie. state !=
        // PENDING_APPROVAL) otherwise create a new one.
        $fr = FacilityRepository::findOrNew($id);
        
        // Set/Update the Facility Repository's state.
        $fr->state = $request->input('state');
        
        switch ($fr->state) {
            case 'PENDING_APPROVAL':
                $fr->data = $this->formatData($request, $now);
                $fr->dateSubmitted = $now;
                $fr->save();
                break;
            
            case 'PUBLISHED':
                $fr->data = $this->publishFacility($fr, $fr->data);
                $fr->facilityId = $fr->data['facility']['id'];
                $fr->reviewerId = Auth::user()->id;
                $fr->reviewerMessage = $request->input('reviewerMessage', null);
                $fr->dateReviewed = $now;
                $fr->update();
                break;
            
            case 'REJECTED':
                $fr->reviewerId = Auth::user()->id;
                $fr->reviewerMessage = $request->input('reviewerMessage', null);
                $fr->dateReviewed = $now;
                $fr->update();
                break;
            
            case 'PENDING_EDIT_APPROVAL':
                // Create a new facility repository record and copy the state
                // and facilityId from the old record.
                $frBeforeUpdate = $fr;
                $fr = new FacilityRepository();
                $fr->facilityId = $frBeforeUpdate->facilityId;
                $fr->state = $frBeforeUpdate->state;
                // We're passing the old facility repository record into
                // the 'formatData' function because we need the existing
                // record's details.
                $fr->data = $this->formatData($request, $now, $frBeforeUpdate);
                $fr->dateSubmitted = $now;
                $fr->save();
                
                // Mark the facility update link record as pending and update
                // its 'frIdAfter' column with the id of the new facility
                // repository record.
                $ful = $frBeforeUpdate->fulB()->open()->first();
                $ful->frIdAfter = $fr->id;
                $ful->status = 'PENDING';
                $ful->datePending = $now;
                $ful->save();
                break;
            
            case 'PUBLISHED_EDIT':
                $fr->reviewerId = Auth::user()->id;
                $fr->reviewerMessage = $request->input('reviewerMessage', null);
                $fr->dateReviewed = $now;
                $fr->data = $this->publishFacility($fr, $fr->data, true);
                $fr->update();
                
                // Admin has reviewed the record, close the token.
                $ful = $fr->fulA()->pending()->first();
                $ful->status = 'CLOSED';
                $ful->dateClosed = $now;
                $ful->update();
                break;
            
            case 'REJECTED_EDIT':
                $fr->reviewerId = Auth::user()->id;
                $fr->reviewerMessage = $request->input('reviewerMessage', null);
                $fr->dateReviewed = $now;
                $fr->update();
                
                // Like 'PUBLISHED_EDIT', the admin has reviewed the record,
                // close it.
                $ful = $fr->fulA()->pending()->first();
                $ful->status = 'CLOSED';
                $ful->dateClosed = $now;
                $ful->update();
                break;
        }
        
        // Generate an event (emails might need to be sent out).
        event(new FacilityRepositoryEvent($fr));
        
        // Return the updated record.
        $f = FacilityRepository::with('reviewer', 'facility')->find($fr->id);
        return $this->toCcArray($f->toArray());
    }
        
    private function formatData($r, $now, $fr = false)
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
            $d['organization']['dateCreated'] = $now;
            $d['organization']['dateUpdated'] = $now;
        }

        // Facility section.
        $d['facility'] = (new Facility($r->data['facility']))->toArray();
        $d['facility']['isPublic'] = true;
        $d['facility']['dateUpdated'] = $now;
        // This part is for updates.
        if ($fr) {
            // ID and date published are maintained.
            $d['facility']['id'] = $fr->facility->id;
            $d['facility']['datePublished'] = $fr->facility->datePublished
                ->toDateTimeString();
        // For new records.
        } else {
            $d['facility']['datePublished'] = $now;         
        }
        
        // Disciplines section.
        $d['disciplines'] = $r->data['disciplines'];
        
        // Sectors section.
        $d['sectors'] = $r->data['sectors'];
        
        // Primary contact section.
        $d['primaryContact'] = (new PrimaryContact($r->data['primaryContact']))
            ->toArray();
        
        // Contacts section.
        // Contacts are optional so check if it exists first.
        if (array_key_exists('contacts', $r->data)) {
            foreach($r->data['contacts'] as $i => $c) {
                $d['contacts'][$i] = (new Contact($c))->toArray();
            }            
        }
        
        // Equipment section.
        foreach($r->data['equipment'] as $i => $e) {
            $d['equipment'][$i] = (new Equipment($e))->toArray();
        }
        
        return $d;
    }
    
    private function publishFacility($fr, $d, $isUpdate = false)
    {        
        // Organization section.
        // If the organization key in $d exists (i.e. a custom organization was
        // selected), check if its name is unique. If it is unique, create the
        // organization and store its key in '$d['facility']['organizationId'].
        // If it is not unique, just grab the existing organization's ID and
        // store it in '$d['facility']['organizationId'].
        if (array_key_exists('organization', $d)) {
            $o = Organization::where('name', $d['organization']['name'])
                ->first();
            if (!$o) {
                $o = Organization::create($d['organization']);
            }
            $d['facility']['organizationId'] = $o->id;
            
            // Delete the organizatio key since we no longer need it.
            unset($d['organization']);
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
        
        // Strip the HTML tags for the search function. We're not including it
        // in '$d' because we don't need the stripped text stored in facility
        // repository.
        $f = $fr->facility()->first();
        $f->descriptionNoHtml = strip_tags($f->description);
        $f->update();
        
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
        
        // Equipment section.
        foreach($d['equipment'] as $i => $e) {
            $d['equipment'][$i] = $f->equipment()->create($e)->toArray();
            $e = $f->equipment()->find($d['equipment'][$i]['id']);
            
            // Strip HTML tags for the search function. Same thing as
            // 'description' for facilities.
            $e->purposeNoHtml = strip_tags($e->purpose);
            $e->specificationsNoHtml = strip_tags($e->specifications);
            $e->update();
        }
        
        return $d;
    }
}
