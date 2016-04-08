@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

A new facility, '{{ $facilityName }}', has been submitted into {{ $settings['APP_ACRONYM'] }} and is pending review.

To view the submission please follow this link: {{ $settings['APP_ADDRESS'] }}/admin/facilities/show?facilityRepositoryId={{ $frId }}
@stop