@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

You have requested...:

http://localhost:9000/#/facilities/form/{{ $id }}/edit?token={{ $token }}
@stop