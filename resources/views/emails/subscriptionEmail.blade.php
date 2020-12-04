@component('mail::message')
# Gratulacje!

Jesteś subskrybentem funkcji premium Nailroom.



Dzięki,<br>
{{ config('app.name') }}

{{ Illuminate\Mail\Markdown::parse($slot) }}

@endcomponent
