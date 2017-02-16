@extends('layouts.emails.master')

@section('content')
Hello {!! $recipientName !!},

The generated report is attached in this email.
@stop
