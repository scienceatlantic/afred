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
        switch ($event->formEntry->status->name) {
            case 'Published':
                // We have to make sure that the listings have been published
                // before sending out the email.
                $numListingsToBeAdded = $event
                    ->formEntry
                    ->listings()
                    ->where('is_in_wp', false)
                    ->orWhere('is_in_algolia', false)
                    ->count();
                
                // If we're still waiting for listings to be created, abort (
                // with the hope that it will be done the next time this is 
                // called).
                if ($numListingsToBeAdded > 0) {
                    $msg = 'Still waiting for all listings to be added before '
                         . 'sending out published notification email.';
                    Log::warning($msg, [
                        'formEntry' => $event->formEntry->toArray()
                    ]);
                    abort(500);
                }
                break;
            case 'Submitted':
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

        // TODO
        $reviewers = User::administrators()->get();

        $isReviewerAlsoAuthor
            = $reviewers->contains('id', $event->formEntry->author->id);

        // TODO: ILO!
        if (!$isReviewerAlsoAuthor) {
            Mail::to('afred.dev@scienceatlantic.ca')
                ->send(new FormEntryStatusUpdateMail($event->formEntry));
        }

        // BUT DON'T EMAIL OTHER ADMINISTRATORS!!!! (i.e. administrators of other installations!)
        foreach($reviewers as $reviewer) {
            Mail::to('afred.dev@scienceatlantic.ca')//$reviewer)
                ->send(new FormEntryStatusUpdateMail($event->formEntry, $reviewer));
        }
    }
}
