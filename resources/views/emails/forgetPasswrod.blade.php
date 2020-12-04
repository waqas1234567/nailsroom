@component('mail::message')
# Reset Password



@component('mail::panel')
    Your forget password  code is {{$code}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
