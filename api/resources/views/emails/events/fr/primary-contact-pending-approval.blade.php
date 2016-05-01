@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

Thank you!

Your information has been successfully submitted into {{ $settings['appName'] }}.

You will received another email once the submission has been reviewed.

In the meantime if you have any questions, please contact <NAME HERE>, <TITLE HERE>, at <EMAIL ADDRESS HERE>.
@stop
