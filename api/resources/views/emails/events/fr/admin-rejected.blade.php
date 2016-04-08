@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

@if ($recipientName == $reviewerName)
This is a confirmation email that you have rejected '{{ $facilityName }}'.
@else
'{{ $facilityName }}' has been rejected by {{ $reviewerName }}.
@endif

The rejected facility can be found here: {{ $settings['APP_ADDRESS'] }}/admin/facilities/show?facilityRepositoryId={{ $frId }}
@stop