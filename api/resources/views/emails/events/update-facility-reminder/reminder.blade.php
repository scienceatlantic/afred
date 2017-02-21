@extends('layouts.emails.master')

@section('content')
Hello {!! $recipientName !!},

The facility listed below has not been updated for at least {{ $interval }} months.

Please take a moment to review your facility and make any updates needed, including adding any new equipment:
{!! $settings['appAddress'] !!}/facilities/{{ $facilityId }}

You may update your facility by using the following link:
{!! $settings['appAddress'] !!}/facilities/update

If your facility is already up-to-date, please disregard this email.

If you have any questions, please contact {!! $settings['personalContactName'] !!}, {!! $settings['personalContactTitle'] !!}, at {!! $settings['personalContactEmail'] !!}.

Thank you for keeping your facility up-to-date.
@stop
