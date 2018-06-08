@component('emails.ucalgary-message')
Hi {!! $formEntry->author->first_name !!},

Congratulations!

Your facility edit has been approved for inclusion in the University of Calgary Research Infrastructure Database.

@if ($formEntry->message)
@component('mail::panel')
A message from {!! $formEntry->reviewer->name !!}:
{!! $formEntry->message !!}
@endcomponent
@endif

Please check your listing(s) here:
@foreach ($formEntry->listings as $listing)
  - <a href="{{ $listing->wp_post_url }}" target="_blank">{{ $listing->entrySection->title }}</a>
@endforeach

You will receive a periodic reminder to check your listing for accuracy. If you
wish to make additional edits to your listing at any time, please follow this
link:<br>
[https://infrastructure.ucalgary.ca/update-facility/](https://infrastructure.ucalgary.ca/update-facility/)

Regards,<br>
UofC Bot
@endcomponent
