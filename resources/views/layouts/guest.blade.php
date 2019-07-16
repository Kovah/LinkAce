<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.header')
</head>
<body>
<div id="app">

    @include('guest.partials.nav')

    <main class="main container">
        @include('partials.alerts')
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ mix('assets/dist/js/dependencies.js') }}"></script>
    <script src="{{ mix('assets/dist/js/app.js') }}"></script>
    <script src="{{ mix('assets/dist/js/fontawesome.js') }}"></script>
    @stack('scripts')

</div>
<div id="loader"><div></div></div>
</body>
</html>
