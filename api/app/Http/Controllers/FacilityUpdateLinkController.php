<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Events.
use App\Events\FacilityUpdateLinksEvent;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Log;

// Models.
use App\Facility;
use App\FacilityUpdateLink;

// Requests.
use App\Http\Requests;
use App\Http\Requests\FacilityUpdateLinkRequest;

class FacilityUpdateLinkController extends Controller
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
    public function index(FacilityUpdateLinkRequest $request)
    {
        $ful = FacilityUpdateLink::with('frB.facility', 'frA');
        
        if ($request->status) {
            $ful->where('status', $request->status);
        }
        
        return $this->pageOrGet($ful);
    }    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FacilityUpdateLinkRequest $request)
    {
        // Local variables to shorten code.
        $id = $request->input('facilityId', null);
        $e = $request->input('email', null);
        
        // Find the facility.
        $f = Facility::findOrFail($id);
        
        // Find the matching primary contact or (regular) contact.
        if (!$c = $f->primaryContact()->where('email', $e)->first()) {
            $c = $f->contact()->where('email', $e)->firstOrFail();
        }
        
        // Only create a new facility update link record if the facility doesn't
        // already have an open/pending facility update link record.
        if (!$f->currentRevision()->first()->fulB()->notClosed()->count()) {
            $ful = new FacilityUpdateLink();
            $ful->frIdBefore = $f->currentRevision->id;
            $ful->editorFirstName = $c->firstName;
            $ful->editorLastName = $c->lastName;
            $ful->editorEmail = $c->email;
            $ful->token = $this->generateUniqueToken();
            $ful->status = 'OPEN';
            $ful->dateOpened = $this->now();
            $ful->save();
            
            event(new FacilityUpdateLinksEvent($ful));
            return $ful;
        } 
        abort(400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacilityUpdateLinkRequest $request, $id)
    {
        $ful = FacilityUpdateLink::findOrFail($id);
        
        // Only allowed to delete a record if it is open. 
        if ($ful->status == 'OPEN') {
            $deletedFul = $this->toCcArray($ful->toArray());
            $ful->delete();
            return $deletedFul;
        }
        abort(400);
    }
    
    /**
     * Generates a unique token.
     * @return {string} Random 25-character string.
     */
    public static function generateUniqueToken()
    {
        while (($token = strtolower(str_random(25)))) {
            if (!FacilityUpdateLink::where('token', $token)->first()) {
                return $token;
            }
        }
    }
}
