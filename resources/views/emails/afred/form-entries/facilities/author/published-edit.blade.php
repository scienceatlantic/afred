@component('mail::message')
Hi {!! $formEntry->author->first_name !!},

Congratulations!

Your facility edit has been approved for inclusion in the Atlantic Facilities
and Research Equipment Database (AFRED).

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
link:
[https://localhost/afred-wp-demo/update-facility](https://localhost/afred-wp-demo/update-facility)

If you have any questions or comments, please contact Lois Whitehead, Science
Atlantic's Executive Director, at
[lois.whitehead@scienceatlantic.ca](lois.whitehead@scienceatlantic.ca).

Regards,<br>
AFRED Bot
@endcomponent
