@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

You have requested...:

{{ $appAddress }}/#/facilities/form/{{ $frIdBefore }}/edit?token={{ $token }}
@stop