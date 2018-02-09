@component('mail::message')
Hi {!! $reviewer->first_name !!},

A new facility, "{!! $formEntry->data['pagination_title'] !!}", has been
submitted into AFRED and is pending review.

To view it, please follow the link below:<br>
[{{ $formEntry->wp_admin_url }}]({{ $formEntry->wp_admin_url }})

Regards,<br>
AFRED Bot
@endcomponent
