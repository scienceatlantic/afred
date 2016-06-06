<?php

namespace App\Listeners;

// Events.
use App\Events\EmailEvent;

// Laravel.
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailListener extends BaseListener
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
     * @param  EmailEvent  $event
     * @return void
     */
    public function handle(EmailEvent $event)
    {
        $e = $event->email;
        $e['data']['settings'] = $this->settings;
        
        // Mail message.
        $this->mail($e['template'], $e['subject'], $e['data'], $e['to'],
            $e['cc'], $e['bcc'], $e['replyTo']);
    }
}
