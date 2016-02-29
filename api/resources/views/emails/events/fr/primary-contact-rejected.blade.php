@extends('layouts.emails.master')

@section('content')
Hello {{ $name }},

Congratulations!

Thank you for your submission to the {{ $settings['APP_NAME'] }}. Your information has not yet been posteed because we need further information. An AFRED team member will be contacting you shortly to help you complete your posting.

In the meantime, if you have any questions or comments, please contact <NAME>, <TITLE>, at <EMAIL>.

Thank you.
@stop