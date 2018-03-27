@component('mail::message')
Hi {!! $formEntry->author->first_name !!},

Thank you!

Your information has been successfully submitted to uCalgary. You will receive
another email once the submission has been reviewed.

Regards,<br>
uCalgary Bot
@endcomponent
