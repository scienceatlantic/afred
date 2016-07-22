@extends('layouts.emails.master')

@section('content')
Hello {!! $recipientName !!},

You have requested to update {!! $facilityName !!}.

Click on the following URL to access your facility for editing. If you are
unable to click on the URL, copy and paste it into your browser.
{!! $settings['appAddress'] !!}/facilities/form/{!! $frIdBefore !!}/edit?token={!! $token !!}

If you did not submit this request, or if you have any questions, please
contact {!! $settings['generalContactEmail'] !!}.
@stop
