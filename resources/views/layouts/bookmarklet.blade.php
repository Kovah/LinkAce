<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.header')
</head>
<body class="bookmarklet">
<div id="loader"><div></div></div>
<div id="app">

    <main class="main container">
        <div class="mb-4 text-center">
            <a class="bookmarklet-logo d-inline-block"
                href="{{ auth()->guest() ? route('front') : route('dashboard') }}">
                {!! displaySVG(public_path('assets/img/logo_linkace.svg'), 90, 33) !!}
            </a>
        </div>

        @include('partials.alerts')
        @yield('content')
    </main>

</div>
</body>
</html>
