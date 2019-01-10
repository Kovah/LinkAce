<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('PAGE_TITLE') ?: config('app.name', 'LinkAce') }}</title>

    <link href="{{ asset('assets/app.css') }}" rel="stylesheet">
</head>
<body class="bookmarklet">
<div id="app">

    <main class="main container">
        <div class="mb-3 text-center">
            <img src="{{ asset('assets/img/logo_linkace.svg') }}" alt="@lang('linkace.linkace')"
                width="81" height="30">
        </div>

        @include('partials.alerts')
        @yield('content')
    </main>

    <script src="{{ asset('assets/dependencies.js') }}"></script>
    @stack('scripts')

</div>
</body>
</html>
