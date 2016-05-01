@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

Congratulations!

Your submission has been approved for inclusion in {{ $settings['appName'] }}.

Please check your listing here {{ $settings['appAddress'] }}/facilities/{{ $facilityId }}.

You will receive an annual reminder to check your listing for accuracy.

If you wish to make edits to your listing at any time, please follow the instructions on this page <URL>.

@if ($iloName && $iloEmail)
A copy of this email has also been sent to your organization's industry liaison office (ILO), {{ $iloName }}, at {{ $iloEmail }}.
@endif

@if ($reviewerName && $reviewerMessage)
A message from an AFRED staff member has been included in this email:
{{ $reviewerName }}: {!! $reviewerMessage !!}
@endif

If you have any questions or comments, please contact <NAME>, <TITLE>, at <EMAIL>.

Thank you!
@stop
