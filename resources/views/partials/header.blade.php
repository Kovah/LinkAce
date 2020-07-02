<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ systemsettings('system_page_title') ?: config('app.name', 'LinkAce') }}</title>

@include('partials.favicon')

@if(usersettings('darkmode_setting') === '1')
    <link href="{{ mix('assets/dist/css/app-dark.css') }}" rel="stylesheet">
@elseif(usersettings('darkmode_setting') === '2')
    <style type="text/css"><?php include public_path('assets/dist/css/loader.css') ?></style>
    <meta name="darkmode" content="1">
    <link rel="stylesheet"
        data-light-href="{{ mix('assets/dist/css/app.css') }}"
        data-dark-href="{{ mix('assets/dist/css/app-dark.css') }}">
@else
    <link href="{{ mix('assets/dist/css/app.css') }}" rel="stylesheet">
@endif

<script defer src="{{ mix('assets/dist/js/dependencies.js') }}"></script>
<script defer src="{{ mix('assets/dist/js/app.js') }}"></script>
<script defer src="{{ mix('assets/dist/js/fontawesome.js') }}"></script>

<meta property="la-app-data" content="{{ json_encode([
    'user' => [
        'token' => csrf_token(),
    ],
    'routes' => [
        'fetch' => [
            'searchLists' => route('fetch-lists'),
            'searchTags' => route('fetch-tags'),
            'existingLinks' => route('fetch-existing-links'),
            'htmlForUrl' => route('fetch-html-for-url'),
            'updateCheck' => route('fetch-update-check'),
            'generateApiToken' => route('generate-api-token'),
            'generateCronToken' => route('generate-cron-token'),
        ]
    ]
]) }}">
