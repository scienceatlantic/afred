@component('mail::message')
Hi {!! $reviewer->first_name !!},

A new facility,
<a href="{{ $formEntry->wp_admin_url }}" target="_blank">{!! $formEntry->data['pagination_title'] !!}</a>,
has been submitted into AFRED and is pending review.

Regards,<br>
AFRED Bot
@endcomponent
