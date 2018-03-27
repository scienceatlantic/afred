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
        // TODO!
        //$event->token->user->email;
        Mail::to('afred.dev@scienceatlantic.ca')
            ->send(new FormEntryTokenMail($event->token, $event->formEntry));
    }
}
