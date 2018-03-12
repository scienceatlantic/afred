<?php

namespace App\Mail;

use App\FormEntry;
use App\FormEntryToken as Token;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FormEntryToken extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public $formEntry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Token $token, FormEntry $formEntry)
    {
        $this->token = $token;
        $this->formEntry = $formEntry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // emails.<dir>.form-entry-tokens.<form>.opened>
        $template = 'emails.'
            . $this->formEntry->form->directory->resource_folder
            . '.form-entry-tokens.'
            . $this->formEntry->form->resource_folder
            . '.opened';
            
        $subject = view("$template-subject", ['formEntry' => $this->formEntry]);
        
        return $this->subject($subject)->markdown($template);
    }
}
