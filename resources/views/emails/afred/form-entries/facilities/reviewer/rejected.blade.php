@component('mail::message')
Hi {!! $reviewer->first_name !!},

@if ($reviewer->id === $formEntry->reviewer->id)
This is a confirmation email that you have rejected
<a href="{!! $formEntry->wp_admin_url !!}" target="_blank">"{!! $formEntry->data['pagination_title'] !!}"</a>.
@else
<a href="{!! $formEntry->wp_admin_url !!}" target="_blank">"{!! $formEntry->data['pagination_title'] !!}"</a>
has been rejected by {!! $reviewer->name !!}.
@endif

Regards,<br>
AFRED Bot
@endcomponent
