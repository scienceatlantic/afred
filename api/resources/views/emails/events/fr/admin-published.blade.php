@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

'{{ $facility }}' has been approved.

It is located at: {{ $settings['APP_ADDRESS'] }}/#/facilities/{{ $facilityId }}
@stop