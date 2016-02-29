@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

Congratulations!

Your submission has been approved for inclusion in the {{ $settings['APP_NAME'] }}.

Please check your listing here {{ $settings['APP_ADDRESS'] }}/facilities/{{ $facilityId }}. You will receive an annual reminder to check your listing for accuracy. If you wish to make edits to your listing at any time, please follow the instructions on this page <URL>.

@if (true)
A copy of this email has also been sent to your organization's industry liaison office (ILO) at <EMAIL HERE>
@endif

If you have any questions or comments, please contact <NAME>, <TITLE>, at <EMAIL>.

Thank you!
@stop