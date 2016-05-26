@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

@if ($recipientName == $reviewerName)
This is a confirmation email that you have approved an edit of
'{{ $facilityName }}'.
@else
An edit of '{{ $facilityName }}' has been approved by {{ $reviewerName }}.
@endif

The facility can be found here:
{{ $settings['appAddress'] }}/#/facilities/{{ $facilityId }}
@stop
