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
        $sPfx = $this->settings['emailSubjectPrefix'];
        
        // Add the facility repository ID to the email subject prefix.
        $sPfx .= '(FR-ID: ' . $event->fr->id. ') ';
        
        // Get the reviewer's name (if applicable).
        $reviewer = $event->fr->reviewer()->first();
        $reviewerName = $reviewer ? $reviewer->getFullName() : null;
        
        // Stores the recipient's details.
        $c = [];
        
        // Primary contact details.
        if ($event->fr->state == 'PENDING_APPROVAL'
            || $event->fr->state == 'PUBLISHED'
            || $event->fr->state == 'REJECTED') {
            $c['name'] = $event->fr->data['primaryContact']['firstName']
                       . ' ' . $event->fr->data['primaryContact']['lastName'];
            $c['email'] = $event->fr->data['primaryContact']['email'];
        // Get the details of the person that submitted the edit request.
        } else if ($event->fr->state == 'PENDING_EDIT_APPROVAL'
            || $event->fr->state == 'PUBLISHED_EDIT'
            || $event->fr->state == 'REJECTED_EDIT') {
            $c['name'] = $event->ful->getFullName();
            $c['email'] = $event->ful->editorEmail;
        }
        
        // Declare the $ilo variable here first and add data to it later if
        // we need it.
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
            'settings'        => $this->settings
        ];
        
        switch ($event->fr->state) {
            // Emails are sent out to all admins and the primary contact.
            case 'PENDING_APPROVAL':    
                // Administrator section.
                $adminT = $tPfx . 'admin-pending-approval';
                $adminS = $sPfx . 'Facility Submitted';             
                
                // Primary contact section.
                $cT = $tPfx . 'primary-contact-pending-approval';
                $cS = $sPfx . 'Facility Submission Received';
                break;
            
            // Emails are sent out to all admins, the primary contact, and the
            // ILO (if applicable).
            case 'PUBLISHED':
                // Administrator section.
                $adminT = $tPfx . 'admin-published';
                $adminS = $sPfx . 'Facility Approved';             
                
                // Primary contact section.
                $cT = $tPfx . 'primary-contact-published';
                $cS = $sPfx . 'Facility Approved';
                
                // ILO section (if applicable).
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
                $adminS = $sPfx . 'Facility Rejected';
                
                // Primary contact section.
                $cT = $tPfx . 'primary-contact-rejected';
                $cS = $sPfx . 'Facility Submission Follow-up Required';                              
                break;
            
            // Emails are sent out the all admins and the primary contact.
            case 'PENDING_EDIT_APPROVAL':
                // Administrator section.
                $adminT = $tPfx . 'admin-pending-edit-approval';
                $adminS = $sPfx . 'Facility Edit Submitted';
                
                // Contact section (could either be a primary contact, contact,
                // or admin - i.e. person that submitted the edit request).
                $cT = $tPfx . 'contact-pending-edit-approval';
                $cS = $sPfx . 'Facility Edit Received';
                break;
            
            // Emails are sent out to all admins and the primary contact.
            case 'PUBLISHED_EDIT':
                // Administrator section.
                $adminT = $tPfx . 'admin-published-edit';
                $adminS = $sPfx . 'Facility Edit Approved';
                
                // Contact section (could either be a primary contact, contact,
                // or admin - i.e. person that submitted the edit request).
                $cT = $tPfx . 'contact-published-edit';
                $cS = $sPfx . 'Facility Edit Approved';   
                break;
            
            // Emails are sent out to all admins and the primary contact.
            case 'REJECTED_EDIT':
                // Administrator section.
                $adminT = $tPfx . 'admin-rejected-edit';
                $adminS = $sPfx . 'Facility Edit Rejected';
                
                // Contact section (could either be a primary contact, contact,
                // or admin - i.e. person that submitted the edit request).
                $cT = $tPfx . 'contact-rejected-edit';
                $cS = $sPfx . 'Facility Edit Follow-up Required';   
                break;
        }
        
        // Email all admins.
        foreach(Role::admin()->users()->get() as $admin) {
            $data['recipientName'] = $admin->getFullName();
            $this->mail($adminT, $adminS, $data, [
                'name'  => $admin->getFullName(),
                'email' => $admin->email
            ]);
        }
        
        // Email the contact and ILO (if applicable).
        $data['recipientName'] = $c['name'];
        $this->mail($cT, $cS, $data, $c, $ilo);
    }
}
