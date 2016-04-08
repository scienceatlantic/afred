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
        // Template prefix. This is the location of all the email templates
        // for this listener.
        $tPfx = 'emails.events.fr.'; 
        
        // Email subject prefix.
        $sPfx = $this->_settings['EMAIL_SUBJECT_PREFIX'];
        
        // Email subject suffix. We're attaching the facility repository's ID
        // to all emails as the suffix.
        $sSfx = ' (FR-ID: ' . $event->fr->id. ')';
        
        // Get the reviewer's name (if applicable)
        $temp = $event->fr->reviewer()->first();
        $reviewerName = $temp ? $temp->getFullName() : null;
        
        // Get the primary contact's details (name and email).
        $pc = [];
        $pc['name'] = $event->fr->data['primaryContact']['firstName']
                    . ' ' . $event->fr->data['primaryContact']['lastName'];
        $pc['email'] = $event->fr->data['primaryContact']['email'];
        
        // Declare the $ilo variable here first. We're only going to initialise
        // it if we need it.
        $ilo = [];
        
        // Data that will be passed to the email templates.
        $data = [
            'recipientName'   => '', // Placeholder for the recipient's name.
            'facilityId'      => $event->fr->facilityId,
            'facilityName'    => $event->fr->data['facility']['name'],
            'frId'            => $event->fr->id,
            'reviewerName'    => $reviewerName,
            'reviewerMessage' => $event->fr->reviewerMessage,
            'iloName'         => '', // Placeholder for the ILO's name.
            'iloEmail'        => '', // Placeholder for the ILO's email.
            'settings'        => $this->_settings
        ];
        
        switch ($event->fr->state) {
            // Emails are sent out to all admins and the primary contact.
            case 'PENDING_APPROVAL':    
                // Administrator section.
                $adminT = $tPfx . 'admin-pending-approval';
                $adminS = $sPfx . 'New research infrastructure information submitted' . $sSfx;             
                
                // Primary contact section.
                $pcT = $tPfx . 'primary-contact-pending-approval';
                $pcS = $sPfx . 'Research infrastructure information received' . $sSfx;
                break;
            
            // Emails are sent out to all admins, the primary contact, and the
            // ILO (if applicable).
            case 'PUBLISHED':
                // Administrator section.
                $adminT = $tPfx . 'admin-published';
                $adminS = $sPfx . 'New research infrastructure information published' . $sSfx;             
                
                // Primary contact section.
                $pcT = $tPfx . 'primary-contact-published';
                $pcS = $sPfx . 'Research infrastructure information published' . $sSfx;
                
                // ILO section.
                $temp = $event->fr->facility()->first();
                $temp = $temp ? $temp->organization()->first()->ilo() : null;
                $temp = $temp ? $temp->first() : null;
                $ilo['name'] = $temp ? $temp->getFullName() : null;
                $ilo['email'] = $temp ? $temp->email : null;
                $data['iloName'] = $ilo['name'];
                $data['iloEmail'] = $ilo['email'];
                break;
            
            // Emails are sent out to all admins, the primary contact, and the
            // ILO (if applicable).
            case 'REJECTED':
                // Administrator section.
                $adminT = $tPfx . 'admin-rejected';
                $adminS = $sPfx . 'New research infrastructure information rejected' . $sSfx;
                
                // Primary contact section.
                $pcT = $tPfx . 'primary-contact-rejected';
                $pcS = $sPfx . 'Research infrastructure information rejected' . $sSfx;                              
                break;
            
            // Emails are sent out the all admins and the primary contact.
            case 'PENDING_EDIT_APPROVAL':
                // Administrator section.
                $adminT = $tPfx . 'admin-pending-edit-approval';
                $adminS = $sPfx . 'New research infrastructure information edit submitted' . $sSfx;
                
                // Primary contact section.
                $pcT = $tPfx . 'primary-contact-pending-edit-approval';
                $pcS = $sPfx . 'Research infrastructure information edit received' . $sSfx;                  
                break;
            
            // Emails are sent out to all admins and the primary contact.
            case 'PUBLISHED_EDIT':
                // Administrator section.
                $adminT = $tPfx . 'admin-published-edit';
                $adminS = $sPfx . 'New research infrastructure information edit published' . $sSfx;
                
                // Primary contact section.
                $pcT = $tPfx . 'primary-contact-published-edit';
                $pcS = $sPfx . 'Research infrastructure information edit published' . $sSfx;   
                break;
            
            // Emails are sent out to all admins and the primary contact.
            case 'REJECTED_EDIT':
                // Administrator section.
                $adminT = $tPfx . 'admin-rejected-edit';
                $adminS = $sPfx . 'New research infrastructure information edit rejected' . $sSfx;
                
                // Primary contact section.
                $pcT = $tPfx . 'primary-contact-rejected-edit';
                $pcS = $sPfx . 'Research infrastructure information edit rejected' . $sSfx;   
                break;
        }
        
        // Email all admins.
        foreach(Role::admin()->users()->get() as $admin) {
            $data['recipientName'] = $admin->getFullName();
            $this->_mail($adminT, $adminS, $data, [
                'name'  => $admin->getFullName(),
                'email' => $admin->email
            ]);
        }
        
        // Email primary contact and ILO (if applicable)
        $data['recipientName'] = $pc['name'];
        $this->_mail($pcT, $pcS, $data, $pc);
        //$this->_mail($pcT, $pcS, $data, $pc, $ilo); // Uncomment this line
                                                      // when we're actually
                                                      // testing ILO func.
    }
}
