@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

Thank you for using {{ $appAcronym }}!

Your facility, '{{ $facility }}', has been submitted for review.

@stop