@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

Thank you for using {{ $settings['APP_ACRONYM'] }}!

Your facility, '{{ $facility }}', has been submitted for review.

@stop