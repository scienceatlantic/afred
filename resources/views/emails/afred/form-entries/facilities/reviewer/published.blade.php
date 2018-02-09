@component('mail::message')
Hi {{ $reviewer->first_name }},

@if ($reviewer->id === $formEntry->reviewer->id)
This is a confirmation email that you have approved
"{!! $formEntry->data['pagination_title'] !!}".
@else
"{!! $formEntry->data['pagination_title'] !!}" has been approved by
{!! $formEntry->reviewer->name !!}.
@endif

The facility can be found here:
{!! $formEntry->wp_admin_url !!}

Regards,<br>
AFRED Bot
@endcomponent
