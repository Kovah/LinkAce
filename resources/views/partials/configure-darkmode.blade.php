@if(usersettings('darkmode_setting') === '1'
    || (request()->is('guest/*') && systemsettings('guest_darkmode_setting') === '1'))
    <link href="{{ mix('assets/dist/css/app-dark.css') }}" rel="stylesheet">
@elseif(usersettings('darkmode_setting') === '2'
    || (request()->is('guest/*') && systemsettings('guest_darkmode_setting') === '2'))
    <style type="text/css"><?php include public_path('assets/dist/css/loader.css') ?></style>
    <meta name="darkmode" content="1">
    <link rel="stylesheet"
        data-light-href="{{ mix('assets/dist/css/app.css') }}"
        data-dark-href="{{ mix('assets/dist/css/app-dark.css') }}">
@else
    <link href="{{ mix('assets/dist/css/app.css') }}" rel="stylesheet">
@endif
