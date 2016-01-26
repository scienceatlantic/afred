@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

Your facility, '{{ $facility }}', has been approved!

It is located at: {{ $facility }}/#/facilities/{{ $facilityId }}

@stop