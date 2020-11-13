@if(usersettings('darkmode_setting') === '1'
    || (request()->is('guest/*') && systemsettings('guest_darkmode_setting') === '1'))
    <link href="{{ mix('assets/dist/css/app-dark.css') }}" rel="stylesheet">
@elseif(usersettings('darkmode_setting') === '2'
    || (request()->is('guest/*') && systemsettings('guest_darkmode_setting') === '2'))
    <link rel="stylesheet" media="(prefers-color-scheme: light)" href="{{ mix('assets/dist/css/app.css') }}">
    <link rel="stylesheet" media="(prefers-color-scheme: dark)" href="{{ mix('assets/dist/css/app-dark.css') }}">
@else
    <link href="{{ mix('assets/dist/css/app.css') }}" rel="stylesheet">
@endif
