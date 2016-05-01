<?php

namespace App\Listeners;

// Events.
use App\Events\FacilityUpdateLinksEvent;

// Laravel.
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// Models.
use App\Setting;
use App\Role;

// Misc.
use Log;
use Mail;

class FacilityUpdateLinksListener extends BaseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle the event.
     *
     * @param  FacilityUpdateLinksEvent  $event
     * @return void
     */
    public function handle(FacilityUpdateLinksEvent $event)
    {
        $email = $event->ful->email;
        $email = 'prasad@scienceatlantic.ca';
        $template = 'emails.events.ful.token-requested'; 
        $subject = $this->_settings['emailSubjectPrefix'] . 'Facility update request (fr #' . $event->ful->frIdBefore . ')';
        
        $data = [
            'recipientName' => $event->ful->getFullName(),
            'frIdBefore'    => $event->ful->frIdBefore,
            'token'         => $event->ful->token,
            'settings'      => $this->_settings  
        ];
        
        $to = [
            'name'  => $event->ful->getFullName(),
            'email' => $event->ful->editorEmail
        ];
        
        $bcc = [];
        foreach(Role::admin()->users()->get() as $admin) {
            array_push($bcc, [
                'name'  => $admin->getFullName(),
                'email' => $admin->email
            ]);
        }
        
        $this->_mail($template, $subject, $data, $to, $bcc);
    }
}
