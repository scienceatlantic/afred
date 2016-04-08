@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

@if ($recipientName == $reviewerName)
This is a confirmation email that you have rejected an edit of '{{ $facilityName }}'.
@else
An edit of '{{ $facilityName }}' has been rejected by {{ $reviewerName }}.
@endif

The rejected edit of the facility can be found here: {{ $settings['APP_ADDRESS'] }}/admin/facilities/show?facilityRepositoryId={{ $frId }}
@stop