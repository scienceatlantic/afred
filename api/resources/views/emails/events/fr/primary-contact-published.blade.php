@extends('layouts.emails.master')

@section('content')
Hello {!! $recipientName !!},

Congratulations!

Your submission has been approved for inclusion in the
{!! $settings['appName'] !!} ({!! $settings['appShortName'] !!}).

@if ($reviewerName && $reviewerMessage)
--
A message from {!! $reviewerName !!}:
{!! $reviewerMessage !!}
--
@endif

Please check your listing here
{!! $settings['appAddress'] !!}/facilities/{!! $facilityId !!}.

You will receive an annual reminder to check your listing for accuracy. If you
wish to make edits to your listing at any time, please follow the instructions
on this page.
{!! $settings['appAddress'] !!}/facilities/update.

@if ($iloName && $iloEmail)
A copy of this email has also been sent to your organization's industry liaison
officer (ILO), {!! $iloName !!}, at {!! $iloEmail !!}.
@endif

If you have any questions or comments, please contact {!! $settings['personalContactName'] !!},
{!! $settings['personalContactTitle'] !!}, at {!! $settings['personalContactEmail'] !!}.
@stop
