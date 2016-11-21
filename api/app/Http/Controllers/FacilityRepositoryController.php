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
        $fr = FacilityRepository::with('reviewer', 'facility',
            'publishedFacility');
        
        // Narrow down query by state.
        switch ($request->input('state')) {
            case 'PENDING_APPROVAL':
            case 'PENDING_EDIT_APPROVAL':
                $fr->pendingApproval(true);
                break;
            case 'PUBLISHED':
            case 'PUBLISHED_EDIT':
                $fr->published((bool) $request->input('visibility', true));
                break;
            case 'REJECTED':
                $fr->rejected();
                break;
            case 'REJECTED_EDIT':
                $fr->rejectedEdit();
                break;
            case 'DELETED':
                $fr->removed();
                break;
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
            'updateRequests' => function($query) {
                $query->notClosed();
            },
            'originRequest' => function($query) {
                $query->notClosed();
            },
            'reviewer',
            'facility',
            'publishedFacility'
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
        if ($request->input('state') == 'PENDING_APPROVAL') {
            $fr = new FacilityRepository();
        } else {
            $fr = FacilityRepository::findOrFail($id);
        }

        // Set/Update the Facility Repository's state.
        $fr->state = $request->input('state');
        
        // Placeholder for a facility update link record.
        $ful = new FacilityUpdateLink();
        
        switch ($fr->state) {
            case 'PENDING_APPROVAL':
                $fr->data = $this->formatFrData($request);
                $fr->dateSubmitted = $now;
                $fr->save();
                break;
            case 'PUBLISHED':
                $fr->data = $this->publishFacility($fr);
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
                // Make a copy of the current facility repository record
                // before we make a copy to store the edited data (we still
                // need its facility id and link to the facility update link
                // record).
                $originalFr = $fr;
                $fr = new FacilityRepository();
                $fr->facilityId = $originalFr->facilityId;
                $fr->state = 'PENDING_EDIT_APPROVAL';
                $fr->data = $this->formatFrData($request, true);
                $fr->dateSubmitted = $now;
                $fr->save();
                
                // Mark the facility update link record as pending and update
                // its 'frIdAfter' column with the id of the new facility
                // repository record.
                $ful = $originalFr->updateRequests()->open()->first();
                $ful->frIdAfter = $fr->id;
                $ful->status = 'PENDING';
                $ful->datePending = $now;
                $ful->save();
                break;            
            case 'PUBLISHED_EDIT':
                $fr->reviewerId = Auth::user()->id;
                $fr->reviewerMessage = $request->input('reviewerMessage', null);
                $fr->dateReviewed = $now;
                $fr->data = $this->publishFacility($fr, true);
                $fr->update();
                
                // Admin has reviewed the record, close the token.
                $ful = $fr->originRequest()->pending()->first();
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
                $ful = $fr->originRequest()->pending()->first();
                $ful->status = 'CLOSED';
                $ful->dateClosed = $now;
                $ful->update();
                break;
        }
        
        // Generate an event (emails might need to be sent out).
        event(new FacilityRepositoryEvent($fr, $ful));
        
        // Return the updated record.
        $fr = FacilityRepository::with('reviewer', 'facility')->find($fr->id);
        return $this->toCcArray($fr->toArray());
    }

    private function formatFrData($r, $isUpdate = false)
    {
        // Will hold all the data that will be returned by the function.
        $d = [];
        
        // Organization section.
        // If an organizationId was provided, skip this part (meaning an
        // exising organization was selected). If an organizationId was not
        // provided, store the details (ie. name) in $d.
        if (!array_key_exists('organizationId', $r->data['facility'])
            || !$r->data['facility']['organizationId']) {
            $o = new Organization($r->data['organization']);
            $d['organization'] = $o->toArray();
        }

        // Facility section.
        $d['facility'] = (new Facility($r->data['facility']))->toArray();
        $d['facility']['isPublic'] = true;
        
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
        
        // Remove any unnecessary keys.
        $d = $this->cleanFrDataBeforeReview($d, $isUpdate);
        
        // Sort keys and return.
        return $this->ksortFrData($d);
    }
    
    private function cleanFrDataBeforeReview($d, $isUpdate = false)
    {
        // Facility section.
        if (!$isUpdate) {
            if (array_key_exists('id', $d['facility'])) {
                unset($d['facility']['id']);
            }
            if (array_key_exists('facilityRepositoryId', $d['facility'])) {
                unset($d['facility']['facilityRepositoryId']);
            }
        }
        if (array_key_exists('datePublished', $d['facility'])) {
            unset($d['facility']['datePublished']);
        }
        if (array_key_exists('dateUpdated', $d['facility'])) {
            unset($d['facility']['dateUpdated']);
        }
        
        // Primary contact section.
        if (!$isUpdate) {
            if (array_key_exists('id', $d['primaryContact'])) {
                unset($d['primaryContact']['id']);
            }
            if (array_key_exists('facilityId', $d['primaryContact'])) {
                unset($d['primaryContact']['facilityId']);
            }
        }
        
        // Contacts section.
        if (!$isUpdate && array_key_exists('contacts', $d)) {
            foreach($d['contacts'] as &$c) {       
                if (array_key_exists('id', $c)) {
                    unset($c['id']);
                }
                if (array_key_exists('facilityId', $c)) {
                    unset($c['facilityId']);
                }
            }
        }
        
        // Equipment section.
        if (!$isUpdate) {
            foreach($d['equipment'] as &$e) {
                if (array_key_exists('id', $e)) {
                    unset($e['id']);
                }
                if (array_key_exists('facilityId', $e)) {
                    unset($e['facilityId']);
                }
            }                
        }
        
        return $d;
    }
    
    private function ksortFrData($d)
    {
        if (array_key_exists('organization', $d)) {
            ksort($d);
        }
        ksort($d['facility']);
        ksort($d['primaryContact']);
        if (array_key_exists('contacts', $d)) {
            foreach($d['contacts'] as &$c) {
                ksort($c);
            }
        }
        foreach($d['equipment'] as &$e) {
            ksort($e);
        }
        ksort($d);
        return $d;
    }
    
    private function publishFacility($fr, $isUpdate = false)
    {
        // Get current datetime.
        $now = $this->now();
        
        // Copy the data array (we cannot modify the contents directly).
        $d = $fr->data;
        
        // Organization section.
        // If the organization key in $d exists (i.e. a custom organization was
        // selected), check if its name is unique. If it is unique, create the
        // organization and store its key in '$d['facility']['organizationId'].
        // If it is not unique, just grab the existing organization's ID and
        // store it in '$d['facility']['organizationId'].
        if (array_key_exists('organization', $d)) {
            // Before creating the organization, double to check to make sure
            // that an organization with an identical name doesn't already
            // exist.
            $o = Organization::where('name', $d['organization']['name'])
                ->first();
            // If the organization is unique, then we create it.
            if (!$o) {
                $o = new Organization($d['organization']);
                $o->isHidden = false;
                $o->dateCreated = $now;
                $o->dateUpdated = $now;
                $o->save();
            }
            $d['facility']['organizationId'] = $o->id;     
            // Delete the organization key since we no longer need it (we don't
            // need it stored in facility repository after the organization
            // has been created).
            unset($d['organization']);
        }
        
        // Facility section.
        $d['facility']['dateUpdated'] = $now;
        // For updates.
        if ($isUpdate) {
            $d['facility']['id'] = $fr->facility->id;
            $d['facility']['datePublished'] = $fr->facility->datePublished
                ->toDateTimeString();
                
            $fr->facility()->delete();
        // For new records.
        } else {
            $d['facility']['datePublished'] = $now;
        }
        // Do not update the search index yet.
        $f = null;
        Facility::withoutSyncingToSearch(function () use (&$fr, &$d, &$f) {
            $f = $fr->publishedFacility()->create($d['facility']);
        });
        $d['facility'] = $f->toArray();
        // We don't need this key in facility repository.
        if (array_key_exists('descriptionNoHtml', $d['facility'])) {
            unset($d['facility']['descriptionNoHtml']);
        }
        // Strip the HTML tags for the search function.
        $f->descriptionNoHtml = strip_tags($f->description);
        $f->update();
        
        // Disciplines section.
        $f->disciplines()->attach($d['disciplines']);
        
        // Sectors section.
        $f->sectors()->attach($d['sectors']);
        
        // Primary contact section.
        $d['primaryContact'] = $f->primaryContact()
            ->create($d['primaryContact'])->toArray();
        
        // Contacts section.
        // Contacts are optional so we have to check if the key exists.
        if (array_key_exists('contacts', $d)) {
            foreach($d['contacts'] as $i => $c) {
                $d['contacts'][$i] = $f->contacts()->create($c)->toArray();          
            }                       
        }           
        
        // Equipment section.
        foreach($d['equipment'] as $i => $e) {
            // Do not update the search index yet.
            Equipment::withoutSyncingToSearch(function() use (&$f, &$e) {
                $e = $f->equipment()->create($e);
            });
            $d['equipment'][$i] = $e->toArray();
            // The following keys do not have to be in facility repository.
            if (array_key_exists('purposeNoHtml', $d['equipment'][$i])) {
                unset($d['equipment'][$i]['purposeNoHtml']);
            }
            if (array_key_exists('specificationsNoHtml', $d['equipment'][$i])) {
                unset($d['equipment'][$i]['specificationsNoHtml']);
            }
            // Strip HTML tags for the search function.
            $e->purposeNoHtml = strip_tags($e->purpose);
            $e->specificationsNoHtml = strip_tags($e->specifications);
            $e->update();
        }

        // Safe to update the search index.
        $f->searchable();
        $f->equipment()->searchable();
        
        // Sort and return data.
        return $this->ksortFrData($d);
    }
}
