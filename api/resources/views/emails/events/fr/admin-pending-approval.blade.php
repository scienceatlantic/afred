@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

A new facility, '{{ $facilityName }}', has been submitted into {{ $settings['appAcronym'] }} and is pending review.

To view the submission please follow this link: {{ $settings['appAddress'] }}/admin/facilities/show?facilityRepositoryId={{ $frId }}
@stop
