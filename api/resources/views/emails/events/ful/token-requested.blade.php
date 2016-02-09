@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

You have requested...:

{{ $settings['APP_ADDRESS'] }}/#/facilities/form/{{ $frIdBefore }}/edit?token={{ $token }}
@stop