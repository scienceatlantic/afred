@extends('layouts.emails.master')

@section('content')
Hello {{ $recipientName }},

Thank you!

Your edit has been successfully submitted into {{ $settings['APP_NAME'] }}.

You will received another email once the edit has been reviewed.

In the meantime if you have any questions, please contact <NAME HERE>, <TITLE HERE>, at <EMAIL ADDRESS HERE>.
@stop