@extends('layouts.emails.master')

@section('content')
Hello {{$fer->firstName}} {{$fer->lastName}},

You have requested...:

http://localhost:9000/#/facilities/form/{{$fer->frhBeforeUpdateId}}/edit?token={{$fer->token}}
@stop