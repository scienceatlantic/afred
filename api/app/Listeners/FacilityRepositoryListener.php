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
        $facility = $fr->data['facility']['name'];
        $templatePrefix = 'emails.events.fr.';
        $subjectPrefix = $this->_settings['EMAIL_SUBJECT_PREFIX'];
        
        // Data for the email templates.
        $data = [
            'name'     => '', // Placeholder for the recipient's name.
            'facility' => $facility,
            'settings' => $this->_settings
        ];
        
        switch ($fr->state) {
            // Emails are sent out to all admins and the primary contact.
            case 'PENDING_APPROVAL':    
                // Administrator section.
                $template = $templatePrefix . 'admin-pending-approval';
                $subject = $subjectPrefix
                    . 'New Submission (' . $facility . ')';              
                
                foreach(Role::admin()->users()->get() as $a) {
                    $data['name'] = $a->firstName . ' ' . $a->lastName;
                    $this->_mail($template, $subject, $data, [
                        'name'  => $a->firstName . ' ' . $a->lastName,
                        'email' => $a->email
                    ]);
                }
                
                // Primary contact section.
                $template = $templatePrefix
                    . 'primary-contact-pending-approval';
                $subject = $subjectPrefix . 'Submission Received';
                $pc = $fr->data['primaryContact'];
                
                $data['name'] = $pc['firstName'] . ' ' . $pc['lastName'];
                $this->_mail($template, $subject, $data, [
                    'name'  => $pc['firstName'] . ' ' . $pc['lastName'],
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
