<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.header')
</head>
<body>
<div id="app">

    @include('partials.nav')

    <main class="main container">
        @include('partials.alerts')
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('assets/dist/js/dependencies.js') }}"></script>
    <script src="{{ asset('assets/dist/js/app.js') }}"></script>
    @stack('scripts')

</div>
<div id="loader"><div></div></div>
</body>
</html>
