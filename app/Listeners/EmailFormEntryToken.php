<?php

namespace App\Listeners;

use App\Events\FormEntryTokenCreated;
use App\Mail\FormEntryToken as FormEntryTokenMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EmailFormEntryToken implements ShouldQueue
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
     * @param  FormEntryTokenCreated  $event
     * @return void
     */
    public function handle(FormEntryTokenCreated $event)
    {
        $administrators = $event->formEntry
            ->form
            ->directory
            ->users()
            ->administrators()
            ->active()
            ->get();

        $editors = $event->formEntry
            ->form
            ->directory
            ->users()
            ->editors()
            ->active()
            ->get();

        $others = $administrators->concat($editors);

        if(env('MAIL_HOST', false) == 'smtp.mailtrap.io'){
            sleep(5); //use usleep(500000) for half a second or less
            $others = [];
        }

        Mail::to($event->token->user->email)
            ->bcc($others)
            ->send(new FormEntryTokenMail($event->token, $event->formEntry));
    }
}
