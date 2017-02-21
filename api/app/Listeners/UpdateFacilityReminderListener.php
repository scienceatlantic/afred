<?php

namespace App\Listeners;

// Events.
use App\Events\UpdateFacilityReminderEvent;

// Laravel.
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// Models.
use App\Facility;

// Misc.
use Log;

class UpdateFacilityReminderListener extends BaseListener
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
     * @param  UpdateFacilityReminderEvent  $event
     * @return void
     */
    public function handle(UpdateFacilityReminderEvent $event)
    {
        $facility = $event->facility;
        $pc = $event->facility->primaryContact;
        
        $template = 'emails.events.update-facility-reminder.reminder';
        $subject = $this->settings['emailSubjectPrefix'] 
                 . 'Reminder to Keep Your Facility Up-to-date ('
                 . $facility->name 
                 . ')';
        $to = [
            'name' => $pc->firstName . ' ' . $pc->lastName,
            'email' => $pc->email
        ];
        $data = [
            'recipientName' => $pc->firstName . ' ' . $pc->lastName,
            'facilityId'    => $facility->id,
            'facility'      => $facility->name,
            'interval'      => $event->interval,
            'settings'      => $this->settings
        ];

        $this->mail($template, $subject, $data, $to);
    }
}
