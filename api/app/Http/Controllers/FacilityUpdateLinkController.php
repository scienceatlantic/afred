<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Events.
use App\Events\FacilityEditTokenRequestedEvent;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Log;

// Models.
use App\Facility;
use App\Contact;
use App\PrimaryContact;
use App\FacilityUpdateLink;
use App\FacilityRepository;

// Requests.
use App\Http\Requests;

class FacilityUpdateLinkController extends Controller
{
    function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $email = $request->input('email', null);        
        
        // Find all matching contacts and grab their facility IDs.
        $cF = Contact::where('email', $email)->select('facilityId');
        
        // Find all matching primary contacts and grab their facility IDs.
        $pcF = PrimaryContact::where('email', $email)->select('facilityId');
        
        // Add the two results together and grab all the matching facilities.
        $ids = $cF->union($pcF)->get()->toArray();
        $f = Facility::whereIn('facilities.id', $ids)
            ->with(['revision.tokens' => function($query) {
                    $query->notClosed();
                }
            ]);
        
        $f = $this->_paginate ? $f->paginate($this->_itemsPerPage) : $f->get();        
        return $this->_toCamelCase($f->toArray());  
    }    
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateToken(Request $request)
    {
        $facilityId = $request->input('facilityId', null);
        $email = $request->input('email', null);
        
        // Grab the matching facility along with the required relationships.
        $f = Facility::with([
            'revision.tokens' => function($query) {
              $query->open();  
            },
            'primaryContact' => function($query) use ($email) {
                $query->where('email', $email); 
            },
            'contacts' => function($query) use ($email) {
                $query->where('email', $email);
            }
        ])->findOrFail($facilityId);
        
        // Check if at least one matching contact was found.
        if (!($c = $f->primaryContact)) {
            $c = $f->contacts->firstOrFail();
        }
          
        if (!count($f->revision->tokens()->notClosed()->get())) {
            $ful = FacilityUpdateLink::create([
                'frIdBefore'    => $f->revision->id,
                'firstName'     => $c->firstName,
                'lastName'      => $c->lastName,
                'email'         => $c->email,
                'token'         => $this->_generateUniqueToken(),
                'status'        => 'OPEN',
                'dateRequested' => $this->_now()
            ]);
            
            event(new FacilityEditTokenRequestedEvent($ful));
        } else {
            return response('Not found', 404);
        }
        
        return $ful;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyToken($id)
    {
        //
    }
    
    private function _generateUniqueToken()
    {
        while (($token = strtolower(str_random(25)))) {
            if (!FacilityUpdateLink::where('token', $token)->first()) {
                break;
            }
        }
        
        return $token;
    }
}
