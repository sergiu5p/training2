@component('mail::message')
# Introduction

The body of your message.

{{ $name }}
{{ $email }}
{{ $comments }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
