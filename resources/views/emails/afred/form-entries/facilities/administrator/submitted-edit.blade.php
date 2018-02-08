@component('mail::message')
Hi {!! $recipient->first_name !!},

An edit of "{!! $formEntry->data['pagination_title'] !!}" has been submitted
into AFRED and is pending review.

To view it, please follow this link:
[{{ $formEntry->wp_admin_url }}]({{ $formEntry->wp_admin_url }})

Regards,<br>
AFRED Bot
@endcomponent
