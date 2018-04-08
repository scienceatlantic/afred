@component('emails.ucalgary-message')
Hi {!! $formEntry->author->first_name !!},

Thank you for your submission to uCalgary. Your information has not yet been
posted because we need additional information.

@if ($formEntry->message)
@component('mail::panel')
A message from {!! $formEntry->reviewer->name !!}:
{!! $formEntry->message !!}
@endcomponent
@endif

Regards,<br>
uCalgary Bot
@endcomponent
