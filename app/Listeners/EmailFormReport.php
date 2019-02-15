<?php

namespace App\Listeners;

use App\Events\FormReportRequested;
use App\Mail\FormReport as FormReportMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EmailFormReport implements ShouldQueue
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
     * @param  FormReportRequested  $event
     * @return void
     */
    public function handle(FormReportRequested $event)
    {
        list($file, $filename, $mime) = $event
            ->formReport
            ->generate($event->fileType);

        if(env('MAIL_HOST', false) == 'smtp.mailtrap.io'){
            sleep(5); //use usleep(500000) for half a second or less
        }

        Mail::to($event->user)
            ->send(new FormReportMail(
                $event->formReport,
                $event->user,
                $file,
                $filename,
                $mime
            ));

        // Delete file from server.
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
