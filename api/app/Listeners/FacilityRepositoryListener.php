<?php

namespace App\Listeners;

// Laravel.
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// Misc.
use Log;
use Mail;

// Models.
use App\Setting;
use App\Role;
use App\Events\FacilityRepositoryEvent;

class FacilityRepositoryListener extends BaseListener
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
     * @param  FacilityRepositoryEvent  $event
     * @return void
     */
    public function handle(FacilityRepositoryEvent $event)
    {
        // Variables for use in all cases.
        $fr = $event->fr;
        $f = $fr->data['facility']['name'];
        $tPfx = 'emails.events.fr.';
        $sPfx = $this->_settings['EMAIL_SUBJECT_PREFIX'];
        
        // Data for the email templates.
        $data = [
            'name'     => '', // Placeholder for the recipient's name.
            'facility' => $f,
            'settings' => $this->_settings
        ];
        
        switch ($fr->state) {
            // Emails are sent out to all admins and the primary contact.
            case 'PENDING_APPROVAL':    
                // Administrator section.
                $t = $tPfx . 'admin-pending-approval';
                $s = $sPfx . 'New Submission (' . $f . ')';              
                
                foreach(Role::admin()->users()->get() as $a) {
                    $name = $a->firstName . ' ' . $a->lastName;
                    $data['name'] = $name;
                    
                    $this->_mail($t, $s, $data, [
                        'name'  => $name,
                        'email' => $a->email
                    ]);
                }
                
                // Primary contact section.
                $t = $tPfx . 'primary-contact-pending-approval';
                $s = $sPfx . 'Submission Received';
                
                $pc = $fr->data['primaryContact'];
                $name = $pc['firstName'] . ' ' . $pc['lastName'];
                $data['name'] = $name;
                
                $this->_mail($t, $s, $data, [
                    'name'  => $name,
                    'email' => $pc['email']
                ]);
                break;
            
            // Emails are sent out to all admins, the primary contact, and
            // the ILO (if applicable).
            case 'PUBLISHED':
                // Administrator section.
                
                break;
            
            case 'REJECTED':
                break;
            
            case 'PENDING_EDIT_APPROVAL':
                break;
            
            case 'PUBLISHED_EDIT':
                break;
                
            case 'REJECTED_EDIT':
                break;
        }
    }
}
