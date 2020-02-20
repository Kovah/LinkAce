<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'LinkAce') }}</title>

    <link href="{{ mix('assets/dist/css/app.css') }}" rel="stylesheet">

    @include('partials.favicon')
</head>
<body class="setup">
<div id="app">

    <main class="main container">
        <div class="mb-5 text-center">
            <img src="{{ asset('assets/img/logo_linkace.svg') }}" alt="@lang('linkace.linkace')"
                width="150" height="55">
        </div>

        <div class="card border-danger text-danger mb-3">
            <div class="card-header bg-danger text-white text-large">
                @yield('code') - @yield('title')
            </div>
            <div class="card-body">
                @yield('message')
            </div>
        </div>

        <a href="{{ redirect()->back()->getTargetUrl() }}">Go back</a>
    </main>

    <script src="{{ mix('assets/dist/js/dependencies.js') }}"></script>
    <script src="{{ mix('assets/dist/js/app.js') }}"></script>
    <script src="{{ mix('assets/dist/js/fontawesome.js') }}"></script>
    @stack('scripts')

</div>
<div id="loader"><div></div></div>
</body>
</html>
