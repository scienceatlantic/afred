<?php

namespace App\Listeners;

// Events.
use App\Events\FacilityEditTokenRequestedEvent;

// Laravel.
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// Models.
use App\Setting;
use App\User;

// Misc.
use Log;
use Mail;

class EmailFacilityEditTokenListener extends BaseListener
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
     * @param  FacilityEditTokenRequestedEvent  $event
     * @return void
     */
    public function handle(FacilityEditTokenRequestedEvent $event)
    {
        $email = $event->ful->email;
        $email = 'prasad@scienceatlantic.ca';
        $template = 'emails.events.ful.token-requested'; 
        $subject = $this->_settings['EMAIL_SUBJECT_PREFIX'] . 'Token';
        $name = $event->ful->firstName . ' ' . $event->ful->lastName;
        
        $data = [
            'name'       => $name,
            'frIdBefore' => $event->ful->frIdBefore,
            'token'      => $event->ful->token,
            'settings'   => $this->_settings  
        ];
        
        $to = [
            'name' => $name,
            'email' => $event->ful->email
        ];
        
        $bcc = [];
        foreach(User::admins()->get() as $a) {
            array_push($bcc, [
                'name'  => $a->firstName . ' ' . $a->lastName,
                'email' => $a->email
            ]);
        }
        
        $this->_mail($template, $subject, $data, $to, $bcc);
    }
}
