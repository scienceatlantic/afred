@component('emails.afred-message')
Hi {!! $formEntry->author->first_name !!},

Thank you for your submission to the Atlantic Facilities and Research Equipment
Database. Your information has not yet been posted because we need further
information.

@if ($formEntry->message)
@component('mail::panel')
A message from {!! $formEntry->reviewer->name !!}:<br>
{!! $formEntry->message !!}
@endcomponent
@endif

To complete your listing, or if you have any questions or comments, please
contact Caitlynne Hines, Science Communication Officer, at
<a href="mailto:caitlynne.hines@scienceatlantic.ca">caitlynne.hines@scienceatlantic.ca</a>.

Regards,<br>
AFRED Bot
@endcomponent
