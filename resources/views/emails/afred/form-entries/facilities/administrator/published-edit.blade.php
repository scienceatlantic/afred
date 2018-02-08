@component('mail::message')
Hi {!! $recipient->first_name !!},

@if ($recipient->id === $formEntry->reviewer->id)
This is a confirmation email that you have approved an edit of
"{!! $formEntry->data['pagination_title'] !!}".
@else
An edit of "{!! $formEntry->data['pagination_title'] !!}" has been approved by
{!! $formEntry->reviewer->name !!}.
@endif

The facility can be found here:
{!! $formEntry->wp_admin_url !!}

Regards,<br>
AFRED Bot
@endcomponent
