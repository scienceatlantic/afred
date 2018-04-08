@component('emails.ucalgary-message')
Hi {!! $reviewer->first_name !!},

@if ($reviewer->id === $formEntry->reviewer->id)
This is a confirmation email that you have approved an edit of
<a href="{!! $formEntry->wp_admin_url !!}" target="_blank">{!! $formEntry->data['pagination_title'] !!}</a>.
@else
An edit of
<a href="{!! $formEntry->wp_admin_url !!}" target="_blank">{!! $formEntry->data['pagination_title'] !!}</a>
has been approved by {!! $formEntry->reviewer->name !!}.
@endif

Regards,<br>
uCalgary Bot
@endcomponent
