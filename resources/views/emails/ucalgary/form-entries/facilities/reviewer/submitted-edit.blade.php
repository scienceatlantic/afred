@component('emails.ucalgary-message')
Hi {!! $reviewer->first_name !!},

An edit of
<a href="{{ $formEntry->wp_admin_url }}" target="_blank">{!! $formEntry->data['pagination_title'] !!}</a>
has been submitted into UofC and is pending review.

Regards,<br>
UofC Bot
@endcomponent
