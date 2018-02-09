@component('mail::message')
Hi {!! $reviewer->first_name !!},

@if ($reviewer->id === $formEntry->reviewer->id)
This is a confirmation email that you have rejected
"{!! $formEntry->data['pagination_title'] !!}".
@else
"{!! $formEntry->data['pagination_title'] !!}" has been rejected by
{!! $reviewer->name !!}.
@endif

The rejected facility can be found here:
{!! $formEntry->wp_admin_url !!}
Regards,<br>
AFRED Bot
@endcomponent
