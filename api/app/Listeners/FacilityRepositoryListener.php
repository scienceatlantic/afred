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
use App\SystemUser;
use App\Events\FacilityRepositoryEvent;

class FacilityRepositoryListener extends BaseListener
{
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
        $subjectPrefix = Setting::find('mailSubjectPrefix')->value;
        
        // Data for the email templates.
        $data = [
            'name'        => '', // Recipient's name.
            'facility'    => $facility,
            'appName'     => Setting::find('appName')->value,
            'appAcronym'  => Setting::find('appAcronym')->value,
            'appAddress'  => Setting::find('appAddress')->value,
            'mailAddress' => Setting::find('mailAddress')->value
        ];
        
        switch ($fr->state) {
            // Emails are sent out to all admins and the primary contact.
            case 'PENDING_APPROVAL':    
                // Administrator section.
                $template = $templatePrefix . 'admin-pending-approval';
                $subject = $subjectPrefix
                    . 'New Submission (' . $facility . ')';              
                
                foreach(SystemUser::all() as $a) {
                    $data['name'] = $name;
                    $this->_mail($template, $subject, $data, [
                        'name'  => $a->firstName . ' ' . $a->lastName,
                        'email' => $a->username
                    ]);
                }
                
                // Primary contact section.
                $template = $templatePrefix
                    . 'primary-contact-pending-approval';
                $subject = $subjectPrefix . 'Submission Received';
                $pc = $fr->data['facility']['primaryContact'];
                
                $data['name'] = $name;
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
