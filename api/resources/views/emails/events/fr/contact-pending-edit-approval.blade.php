@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

Thank you!

Your facility edit has been successfully submitted to the
{{ $settings['appName'] }} ({{ $settings['appShortName']}}). You will receive
another email once the submission has been reviewed.

In the meantime if you have any questions, please contact {{ $settings['personalContactName'] }},
{{ $settings['personalContactTitle'] }}, at {{ $settings['personalContactEmail'] }}.
@stop
