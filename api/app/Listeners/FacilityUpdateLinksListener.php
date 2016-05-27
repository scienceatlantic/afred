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
        $template = 'emails.events.ful.token-requested';
        $sPfx = $this->settings['emailSubjectPrefix'] . '(FR-ID: ' . $event->ful->frIdBefore. ') ';
        $subject = $sPfx .'Facility Update Request';
        
        // Template data.
        $data = [
            'recipientName' => $event->ful->getFullName(),
            'facilityName'  => $event->ful->frB()->first()->data['facility']['name'],
            'frIdBefore'    => $event->ful->frIdBefore,
            'token'         => $event->ful->token,
            'settings'      => $this->settings  
        ];
        
        // Recipient that requested the edit.
        $to = [
            'name'  => $event->ful->getFullName(),
            'email' => $event->ful->editorEmail
        ];
        
        // Blind copy all admins.
        $bcc = [];
        foreach(Role::admin()->users()->get() as $admin) {
            array_push($bcc, [
                'name'  => $admin->getFullName(),
                'email' => $admin->email
            ]);
        }
        
        $this->mail($template, $subject, $data, $to, null, $bcc);
    }
}
