<?php

namespace App\Listeners;

use App\Events\FormEntryStatusUpdated;
use App\Mail\FormEntryStatusUpdate as FormEntryStatusUpdateMail;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EmailFormEntryStatusUpdate implements ShouldQueue
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
     * @param  FormEntryStatusUpdated  $event
     * @return void
     */
    public function handle(FormEntryStatusUpdated $event)
    {
        // TODO
        $reviewers = User::administrators()->get();

        $isReviewerAlsoAuthor
            = $reviewers->contains('id', $event->formEntry->author->id);

        // TODO: ILO!
        if (!$isReviewerAlsoAuthor) {
            Mail::to('prasad@scienceatlantic.ca')
                ->send(new FormEntryStatusUpdateMail($event->formEntry));
        }

        // BUT DON'T EMAIL OTHER ADMINISTRATORS!!!! (i.e. administrators of other installations!)
        foreach($reviewers as $reviewer) {
            Mail::to('prasad@scienceatlantic.ca')//$reviewer)
                ->send(new FormEntryStatusUpdateMail($event->formEntry, $reviewer));
        }
    }
}
