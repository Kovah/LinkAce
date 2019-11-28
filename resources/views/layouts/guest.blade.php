<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.header')
</head>
<body>
<div id="loader"><div></div></div>
<div id="app">

    @include('guest.partials.nav')

    <main class="main container">
        <a class="d-block d-md-none mb-4"
            href="{{ auth()->guest() ? route('front') : route('dashboard') }}">
            {!! displaySVG(public_path('assets/img/logo_linkace.svg'), 100, 30) !!}
        </a>

        @include('partials.alerts')
        @yield('content')
    </main>

    @include('partials.footer')

</div>
</body>
</html>
