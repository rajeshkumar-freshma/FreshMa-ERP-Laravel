
@component('mail::message')
# {{ $subject }} - {{  $title }}

{{ $content }}


@component('mail::button', ['url' => @$body['tiny_url'] ])
Invoice Information
@endcomponent

Thanks,<br>
{{ $title }}
@endcomponent

