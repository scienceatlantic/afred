@component('emails.afred-message')
Hi {!! $token->user->first_name !!},

You have requested to update {!! $formEntry->data['pagination_title'] !!}.

Click on the following URL to access your facility for editing. If you are
unable to click on the URL, copy and paste it into your browser.
<a href="{{ $token->wp_edit_url }}" target="_blank">{!! $token->wp_edit_url !!}</a>

If you did not submit this request, or if you have any questions, please contact
<a href="mailto:afred@scienceatlantic.ca">afred@scienceatlantic.ca</a>.

Regards,<br>
AFRED Bot
@endcomponent
