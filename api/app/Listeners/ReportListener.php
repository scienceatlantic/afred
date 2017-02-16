<?php

namespace App\Listeners;

// Events.
use App\Events\ReportEvent;

// Laravel.
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// Models.
use App\User;

// Misc.
use Log;

class ReportListener extends BaseListener
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
     * @param  ReportEvent  $event
     * @return void
     */
    public function handle(ReportEvent $event)
    {
        $template = 'emails.events.report.report-generated';
        $subject = $this->settings['emailSubjectPrefix'] 
                 . ' Report Generated - ' . $event->report['title'];
        $to = [
            'name' => $event->user->getFullName(),
            'email' => $event->user->email
        ];
        $file = [$event->report['full']];
        $data = [
            'recipientName' => $event->user->getFullName(),
            'settings'      => $this->settings
        ];

        $this->mail($template, $subject, $data, $to, null, null, null, $file);
    }
}
