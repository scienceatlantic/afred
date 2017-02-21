@extends('layouts.emails.master')

@section('content')
Hello {!! $recipientName !!},

Congratulations!

Your facility edit has been approved for inclusion in the
{!! $settings['appName'] !!} ({!! $settings['appShortName'] !!}).

@if ($reviewerName && $reviewerMessage)
--
A message from {!! $reviewerName !!}:
{!! $reviewerMessage !!}
--
@endif

Please check your listing here:
{!! $settings['appAddress'] !!}/facilities/{!! $facilityId !!}.

You will receive a periodic reminder to check your listing for accuracy. If you
wish to make additional edits to your listing at any time, please follow this
link.
{!! $settings['appAddress'] !!}/facilities/update.

If you have any questions, please contact {!! $settings['personalContactName'] !!},
{!! $settings['personalContactTitle'] !!}, at {!! $settings['personalContactEmail'] !!}.
@stop
