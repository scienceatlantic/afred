@extends('layouts.emails.master')

@section('content')
Hello {!! $recipientName !!},

Thank you for your submission to the {!! $settings['appName'] !!}.
Your information has not yet been posteed because we need further information.

@if ($reviewerName && $reviewerMessage)
--
A message from {!! $reviewerName !!}:
{!! $reviewerMessage !!}
--
@endif

To complete your listing, or if you have any questions or comments, please
contact {!! $settings['personalContactName'] !!}, {!! $settings['personalContactTitle'] !!}, at {!! $settings['personalContactEmail'] !!}.
@stop
