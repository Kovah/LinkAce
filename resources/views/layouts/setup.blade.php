<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LinkAce') }}</title>

    <link href="{{ mix('assets/dist/css/app.css') }}" rel="stylesheet">

    @include('partials.favicon')
</head>
<body class="setup">
<div id="app">

    <main class="main container">
        <div class="mb-5 h1 text-center text-primary">
            <x-icon.linkace/>
        </div>

        @yield('content')
    </main>

    <script src="{{ mix('assets/dist/js/dependencies.js') }}"></script>
    <script src="{{ mix('assets/dist/js/app.js') }}"></script>
    @stack('scripts')

</div>
</body>
</html>
