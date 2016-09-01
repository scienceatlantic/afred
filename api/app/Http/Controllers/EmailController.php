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
use App\User;
use App\Setting;

// Requests.
use App\Http\Requests;

class EmailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {    
        // Data that will be passed to the event handler.
        $e = [
            // Location of the email template.
            'template' => 'emails.events.email.message',
            
            // Email subject.
            'subject'  => Setting::lookup('emailSubjectPrefix'),
            
            // Email template data.
            'data'     => [
                'type'    => null,
                'subject' => null,
                'date'    => $this->now(false)->toDayDateTimeString(),
                'from'    => null,
                'body'    => $r->body,
            ],
            
            // Email recipieint.
            'to'       => [],
            
            // "cc".
            'cc'       => [],
            
            // "bcc".
            'bcc'      => [],
            
            // Reply to.
            'replyTo'  => [
                'email' => null,
                'name'  => null
            ]
        ];
        
        switch($r->type) {
            // Contact form message.
            case 'contactForm':
                $e['subject'] .= 'Contact Form - ' . $r->subject;
                $e['data']['type'] = 'AFRED Website Contact Form';
                $e['data']['subject'] = $r->subject;
                $e['data']['from'] = $r->name . ' (' . $r->email . ')';
                array_push($e['to'], [
                    'name'  => Setting::lookup('contactFormName'),
                    'email' => Setting::lookup('contactFormEmail')
                ]);
                $e['replyTo']['email'] = $r->email;
                $e['replyTo']['name'] = $r->name;
                
                event(new EmailEvent($e));
                return;
            
            // Springboard Atlantic contact modal when a user is not able to
            // find what they're looking for.
            case 'springboardAtlantic':
                $e['subject'] .= 'Springboard Atlantic Form';
                $e['data']['type'] = 'AFRED Springboard Atlantic Contact Form';
                $e['data']['from'] = $r->name . ' (' . $r->email . ')';
                
                // To Springboard Atlantic.
                array_push($e['to'], [
                    'name'  => Setting::lookup('springboardFormName'),
                    'email' => Setting::lookup('springboardFormEmail')
                ]);
                
                // Bcc all admins and up.
                foreach(User::active()->admins(false)->get() as $admin) {
                    array_push($e['bcc'] , [
                        'name'  => $admin->getFullName(),
                        'email' => $admin->email
                    ]);                    
                }
                
                $e['replyTo']['email'] = $r->email;
                $e['replyTo']['email'] = $r->name;
                
                event(new EmailEvent($e));
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
