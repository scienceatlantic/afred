@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

A new facility called '{{ $facility }}' has been submitted into {{ $settings['APP_ACRONYM'] }} and is pending review.

Please login here: {{ $settings['APP_ADDRESS'] }}/#/login
@stop