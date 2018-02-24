<?php

namespace App\Listeners;

use App\Events\FormEntryUpdate;
use App\Mail\FormEntryUpdate as FormEntryUpdateMail;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EmailFormEntryUpdate implements ShouldQueue
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
     * @param  FormEntryUpdate  $event
     * @return void
     */
    public function handle(FormEntryUpdate $event)
    {
        // TODO
        $reviewers = User::administrators()->get();

        $isReviewerAlsoAuthor
            = $reviewers->contains('id', $event->formEntry->author->id);

        if (!$isReviewerAlsoAuthor) {
            Mail::to('prasad@scienceatlantic.ca')
                ->send(new FormEntryUpdateMail($event->formEntry));
        }

        // BUT DON'T EMAIL OTHER ADMINISTRATORS!!!! (i.e. administrators of other installations!)
        foreach($reviewers as $reviewer) {
            Mail::to('prasad@scienceatlantic.ca')//$reviewer)
                ->send(new FormEntryUpdateMail($event->formEntry, $reviewer));
        }
    }
}
