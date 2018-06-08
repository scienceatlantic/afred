@component('emails.ucalgary-message')
Hi {!! $formEntry->author->first_name !!},

Thank you for your submission to the University of Calgary Research Infrastructure Database. Your information has not yet been posted because we need further information.

@if ($formEntry->message)
@component('mail::panel')
A message from {!! $formEntry->reviewer->name !!}:<br>
{!! $formEntry->message !!}
@endcomponent
@endif

Regards,<br>
UofC Bot
@endcomponent
