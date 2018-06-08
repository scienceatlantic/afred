@component('emails.ucalgary-message')
Hi {!! $reviewer->first_name !!},

A new facility,
<a href="{{ $formEntry->wp_admin_url }}" target="_blank">{!! $formEntry->data['pagination_title'] !!}</a>,
has been submitted into the University of Calgary Research Infrastructure Database and is pending review.<br>
Please review within 3 business days.

Regards,<br>
UofC Bot
@endcomponent
