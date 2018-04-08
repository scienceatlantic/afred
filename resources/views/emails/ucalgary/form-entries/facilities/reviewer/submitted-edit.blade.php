@component('emails.ucalgary-message')
Hi {!! $reviewer->first_name !!},

An edit of
<a href="{{ $formEntry->wp_admin_url }}" target="_blank">{!! $formEntry->data['pagination_title'] !!}</a>
has been submitted into uCalgary and is pending review.

Regards,<br>
uCalgary Bot
@endcomponent
