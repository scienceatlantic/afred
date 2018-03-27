@component('mail::message')
Hi {!! $token->user->first_name !!},

You have requested to update {!! $formEntry->data['pagination_title'] !!}.

Click on the following URL to access your facility for editing. If you are
unable to click on the URL, copy and paste it into your browser.
<a href="{{ $token->wp_edit_url }}" target="_blank">{{ $token->wp_edit_url }}</a>

Regards,<br>
uCalgary Bot
@endcomponent
