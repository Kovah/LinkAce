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

</div>
</body>
</html>
