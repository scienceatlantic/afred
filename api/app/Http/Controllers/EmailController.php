<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Events.
use App\Events\EmailEvent;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Log;

// Models.
use App\Setting;

// Requests.
use App\Http\Requests;

class EmailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Email subject prefix.
        $sPfx = Setting::find('emailSubjectPrefix');
        
    
        $e = [
            'template' => 'emails.events.email.message',
            'subject'  => $sPfx,
            'data'     => [
                'type'    => '',
                'date'    => $this->now(),
                'message' => $request->message,
            ],
            'to'       => [],
            'cc'       => [],
            'bcc'      => []
        ];
        
        switch($request->type) {
            // Contact form message.
            case 'contactForm':
                $e['subject'] .= 'Contact Form - ' . $request->subject;
                $e['data']['type'] = 'AFRED Website Contact Form';
                
                $e['to'] = [
                    'name'  => 'Prasad',
                    'email' => 'prasad@scienceatlantic.ca'
                ];
                
                event(new EmailEvent($e));
                return;
            
            // Springboard Atlantic contact modal when a user is not able to
            // find what they're looking for.
            case 'springboardAtlantic':            
                return;
            
            // Report a mistake in a facility listing.
            case 'facility':                
                return;
            
            // 
            case 'facilityContact':                
                return;
            
            default:
                abort(400);
        }
    }
}
