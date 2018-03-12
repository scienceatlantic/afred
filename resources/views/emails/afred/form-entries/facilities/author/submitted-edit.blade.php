@component('mail::message')
Hi {!! $formEntry->author->first_name !!},

Thank you!

Your facility edit has been successfully submitted to the
Atlantic Facilities and Research Equipment Database (AFRED). You will receive
another email once the submission has been reviewed.

In the meantime if you have any questions, please contact Lois Whitehead,
Science Atlantic's Executive Director, at
[lois.whitehead@scienceatlantic.ca](lois.whitehead@scienceatlantic.ca).

Regards,<br>
AFRED Bot
@endcomponent
