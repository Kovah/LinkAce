<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.header')
</head>
<body class="bookmarklet">
<div id="loader"><div></div></div>
<div id="app">

    <main class="main container">
        <div class="mb-3 text-center">
            <img src="{{ asset('assets/img/logo_linkace.svg') }}" alt="@lang('linkace.linkace')"
                width="81" height="30">
        </div>

        @include('partials.alerts')
        @yield('content')
    </main>

</div>
</body>
</html>
