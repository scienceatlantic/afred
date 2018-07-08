<?php

namespace App\Listeners;

use App\Events\FormEntryStatusUpdated;
use App\Mail\FormEntryStatusUpdate as FormEntryStatusUpdateMail;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Log;

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
        // Empty the cache
        $event->formEntry->emptyCache();

        switch ($event->formEntry->status->name) {
            case 'Submitted':
            case 'Published':
            case 'Rejected':
                // Nothing to do here, just send email below.
                break;
            default:
                // Return because we're not going to email anything if it's
                // not any of those statuses.
                $msg = 'This form entry status, "'
                     . $event->formEntry->status->name
                     . '", does not have a corresponding email template set up'.
                Log::warning($msg, [
                    'formEntry' => $event->formEntry->toArray()
                ]);
                return;
        }

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

        $reviewers = $administrators->concat($editors);

        $isReviewerAlsoAuthor
            = $reviewers->contains('id', $event->formEntry->author->id);

        if (!$isReviewerAlsoAuthor) {
            $mail = Mail::to($event->formEntry->author);

            // Copy ILO if applicable.
            if ($event->formEntry->is_published
                && !$event->formEntry->is_edit
                && $event->formEntry->ilo) {
                $mail->cc($event->formEntry->ilo);
            }

            $mail->send(new FormEntryStatusUpdateMail($event->formEntry));
        }

        foreach($reviewers as $reviewer) {
            $msg = new FormEntryStatusUpdateMail($event->formEntry, $reviewer);
            Mail::to($reviewer)->send($msg);
        }
    }
}
