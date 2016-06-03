@extends('layouts.emails.master')

@section('content')
Hello,

Message details:
Type: {{ $type }}
Date: {{ $date }}

Message:

@stop
