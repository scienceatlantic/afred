@component('emails.ucalgary-message')
Hi {{ $reviewer->first_name }},

@if ($reviewer->id === $formEntry->reviewer->id)
This is a confirmation email that you have approved
"{!! $formEntry->data['pagination_title'] !!}".
@else
"{!! $formEntry->data['pagination_title'] !!}" has been approved by
{!! $formEntry->reviewer->name !!}.
@endif

The published listing(s) can be found here:
@foreach ($formEntry->listings as $listing)
  - <a href="{{ $listing->wp_post_url }}" target="_blank">{{ $listing->entrySection->title }}</a>
@endforeach

Regards,<br>
uCalgary Bot
@endcomponent
