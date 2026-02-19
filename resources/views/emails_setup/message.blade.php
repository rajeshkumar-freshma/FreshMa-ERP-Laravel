@component('mail::message')
# {{ $subject }} - {{  $setting_general->title }}

{!! $message !!}


Thanks,<br>
{{ $setting_general->title }}
@endcomponent
