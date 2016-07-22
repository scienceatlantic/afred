@extends('layouts.emails.master')

@section('content')
Hello {!! $recipientName !!},

An edit of '{!! $facilityName !!}' has been submitted into {!! $settings['appShortName'] !!}
and is pending review.

To view it, please follow this link:
{!! $settings['appAddress'] !!}/admin/facilities/show?facilityRepositoryId={!! $frId !!}
@stop
