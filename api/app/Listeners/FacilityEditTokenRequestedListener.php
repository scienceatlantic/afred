<?php

namespace App\Listeners;

use Log;
use Mail;
use App\Events\FacilityEditTokenRequestedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FacilityEditTokenRequestedListener
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
        $name = $event->fer->firstName . ' ' . $event->fer->lastName;
        $email = $event->fer->email;
        $email = 'prasad@scienceatlantic.ca';
        $subject = 'AFRED 2.0 TEST - Token';
        
        try {
            Mail::send(
                ['text' => 'emails.events.fer.token-requested'],
                ['fer' => $event->fer],
                function($message) use ($email, $name, $subject) {
                    $message->to($email, $name);
                    $message->subject($subject);
                }
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
