@extends('layouts.emails.master')

@section('content')
Hello,

Details:
Type: {!! $type !!}
Date: {{ $date }}
@if ($from)
From: {!! $from !!}
@endif

Message:
{!! $body !!}
@stop
