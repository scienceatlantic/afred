@component('emails.ucalgary-message')
Hi {!! $formEntry->author->first_name !!},

Thank you!

Your facility edit has been successfully submitted to UofC. You will receive
another email once the submission has been reviewed.

Regards,<br>
UofC Bot
@endcomponent
