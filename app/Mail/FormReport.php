<?php

namespace App\Mail;

use App\FormReport as Report;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FormReport extends Mailable
{
    use Queueable, SerializesModels;

    public $formReport;

    public $user;

    public $file;

    public $filename;

    public $mime;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        Report $formReport,
        User $user,
        $file,
        $filename,
        $mime
    ) {
        $this->formReport = $formReport;
        $this->user = $user;
        $this->file = $file;
        $this->filename = $filename;
        $this->mime = $mime;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // emails.<dir>.form-reports.<form>.requested>
        $template = 'emails.'
            . $this->formReport->form->directory->resource_folder
            . '.form-reports.'
            . $this->formReport->form->resource_folder
            . '.requested';

        $subject = view("$template-subject");

        return $this
            ->subject($subject)
            ->markdown($template)
            ->attach($this->file, [
                'as'   => $this->filename,
                'mime' => $this->mime
            ]);
    }
}
