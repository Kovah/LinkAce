@component('mail::message')

This is a test email to check the mail configuration and credentials of your LinkAce setup. If you received this email, everything is good!

@component('mail::button', ['url' => route('dashboard')])
Go to LinkAce
@endcomponent

@lang('link.happy_bookmarking'),<br>
{{ config('app.name') }}
@endcomponent
