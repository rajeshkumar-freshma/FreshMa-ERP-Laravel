@component('mail::message')
# {{ $subject }}

Name: {{ $contact['name'] }}<br>
Email: {{ $contact['email'] }}<br>
Mobile: {{ $contact['mobile'] }}<br>
Message: {{ $contact['message'] }}<br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
