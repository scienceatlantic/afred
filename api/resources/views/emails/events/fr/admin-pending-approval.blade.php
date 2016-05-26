@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

A new facility, '{{ $facilityName }}', has been submitted into {{ $settings['appShortName'] }}
and is pending review.

To view it, please follow the link below:
{{ $settings['appAddress'] }}/#/admin/facilities/show?facilityRepositoryId={{ $frId }}
@stop
