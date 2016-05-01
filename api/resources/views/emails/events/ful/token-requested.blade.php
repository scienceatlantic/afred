@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

You have submitted a request to edit your facility.

Click on the following URL to access your facility for editing. If you are unable to click on the URL, copy and paste it into your browser.
{{ $settings['appAddress'] }}/facilities/form/{{ $frIdBefore }}/edit?token={{ $token }}

If you have any trouble with the link or any questions, please contact <NAME>, <TITLE>, <EMAIL>.
@stop
