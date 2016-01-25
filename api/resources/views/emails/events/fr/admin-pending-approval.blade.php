@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

A new facility called '{{ $facility }}' has been submitted into {{ $appAcronym }} and is pending review.

Please login here: {{ $appAddress }}/#/login

@stop