<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ systemsettings('system_page_title') ?: config('app.name', 'LinkAce') }}</title>

@if(usersettings('darkmode_setting') === '1')
    <link href="{{ mix('assets/dist/css/app-dark.css') }}" rel="stylesheet">
@elseif(usersettings('darkmode_setting') === '2')
    <style><?php include public_path('assets/dist/css/loader.css') ?></style>
    <meta name="darkmode" content="1">
    <link rel="stylesheet"
        data-light-href="{{ mix('assets/dist/css/app.css') }}"
        data-dark-href="{{ mix('assets/dist/css/app-dark.css') }}">
@else
    <link href="{{ mix('assets/dist/css/app.css') }}" rel="stylesheet">
@endif

<meta property="la-app-data" content="{{ json_encode([
    'user' => [
        'token' => csrf_token()
    ],
    'routes' => [
        'ajax' => [
            'existingLinks' => route('ajax-existing-links')
        ]
    ]
]) }}">

<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/img/apple-icon-57x57.png') }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/img/apple-icon-60x60.png') }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/img/apple-icon-72x72.png') }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon-76x76.png') }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/img/apple-icon-114x114.png') }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-icon-120x120.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/img/apple-icon-144x144.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/img/apple-icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/apple-icon-180x180.png') }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/img/android-icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/img/favicon-96x96.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('assets/img/manifest.json') }}">
<meta name="msapplication-TileColor" content="#44679F">
<meta name="msapplication-TileImage" content="{{ asset('assets/img/ms-icon-144x144.png') }}">
<meta name="theme-color" content="#44679F">
