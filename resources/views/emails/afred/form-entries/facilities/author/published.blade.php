@component('mail::message')
Hi {!! $formEntry->author->first_name !!},

Congratulations!

Your submission has been approved for inclusion in the Atlantic Facilities and
Research Equipment Database (AFRED).

@if ($formEntry->message)
@component('mail::panel')
A message from {!! $formEntry->reviewer->name !!}:<br>
{!! $formEntry->message !!}
@endcomponent
@endif

Please check your listing(s) here:
@foreach ($formEntry->listings as $listing)
  - <a href="{{ $listing->wp_post_url }}" target="_blank">{{ $listing->entrySection->title }}</a>
@endforeach

You will receive a periodic reminder to check your listing for accuracy. If you
wish to make edits to your listing at any time, please follow the instructions
on this page:<br>
[https://localhost/afred-wp-demo/update-facility](https://localhost/afred-wp-demo/update-facility)

@if ($formEntry->ilo)
A copy of this email has also been sent to your organization's industry liaison
officer (ILO), {!! $formEntry->ilo->name !!}, at
<a href="mailto:{!! $formEntry->ilo->email !!}">{!! $formEntry->ilo->email !!}</a>.
@endif

If you have any questions or comments, please contact Lois Whitehead, Science
Atlantic's Executive Director, at
<a href="mailto:lois.whitehead@scienceatlantic.ca">lois.whitehead@scienceatlantic.ca</a>.

Regards,<br>
AFRED Bot
@endcomponent
