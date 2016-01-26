<?php

namespace App\Listeners;

// Events.
use App\Events\FacilityEditTokenRequestedEvent;

// Laravel.
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// Models.
use App\Setting;
use App\SystemUser;

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
        //
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
        $subject = Setting::find('mailSubjectPrefix')->value . 'Token';
        $name = $event->ful->firstName . ' ' . $event->ful->lastName;
        
        $data = [
            'name'        => $name,
            'frIdBefore'  => $event->ful->frIdBefore,
            'token'       => $event->ful->token,
            'appName'     => Setting::find('appName')->value,
            'appAcronym'  => Setting::find('appAcronym')->value,
            'appAddress'  => Setting::find('appAddress')->value,
            'mailAddress' => Setting::find('mailAddress')->value
        ];
        
        $to = [
            'name' => $name,
            'email' => $event->ful->email
        ];
        
        $bcc = [];
        foreach(SystemUser::all() as $a) {
            array_push($bcc, [
                'name'  => $a->firstName . ' ' . $a->lastName,
                'email' => $a->username
            ]);
        }
        
        $this->_mail($template, $subject, $data, $to, $bcc);
    }
}
