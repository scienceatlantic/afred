<?php

namespace App\Mail;

use App\FormEntry;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FormEntryStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $formEntry;

    public $reviewer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(FormEntry $formEntry, User $reviewer = null)
    {
        $this->formEntry = $formEntry;
        $this->reviewer = $reviewer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // emails.<dir>.form-entries.<form>.<author|reviewer>.<status><(-edit)>
        $template = 'emails.'
            . $this->formEntry->form->directory->resource_folder
            . '.form-entries.'
            . $this->formEntry->form->resource_folder
            . '.'
            . ($this->reviewer ? 'reviewer.' : 'author.')
            . strtolower($this->formEntry->status->name)
            . ($this->formEntry->is_edit ? '-edit' : '');

        $subject = view("$template-subject", ['formEntry' => $this->formEntry]);

        return $this->subject($subject)->markdown($template);
    }
}
