<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ systemsettings('system_page_title') ?: config('app.name', 'LinkAce') }}</title>

@stack('html-header')
@include('partials.favicon')
@include('partials.social-meta')

@include('partials.configure-darkmode')

<script defer src="{{ mix('assets/dist/js/dependencies.js') }}"></script>
<script defer src="{{ mix('assets/dist/js/app.js') }}"></script>

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
