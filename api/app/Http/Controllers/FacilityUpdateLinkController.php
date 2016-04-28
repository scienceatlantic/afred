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
        $facilityId = $request->input('facilityId', null);
        $email = $request->input('email', null);
        
        // Grab the matching facility along with the required relationships.
        $f = Facility::with([
            'revision.fulsB' => function($query) {
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
          
        if (!count($f->revision->fulsB()->notClosed()->get())) {
            $ful = FacilityUpdateLink::create([
                'frIdBefore'      => $f->revision->id,
                'editorFirstName' => $c->firstName,
                'editorLastName'  => $c->lastName,
                'editorEmail'     => $c->email,
                'token'           => $this->generateUniqueToken(),
                'status'          => 'OPEN',
                'dateOpened'      => $this->now()
            ]);
            
            event(new FacilityUpdateLinksEvent($ful));
        } else {
            abort(404, 'Not found');
        }
        
        return $ful;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    private function generateUniqueToken()
    {
        while (($token = strtolower(str_random(25)))) {
            if (!FacilityUpdateLink::where('token', $token)->first()) {
                return $token;
            }
        }
    }
}
