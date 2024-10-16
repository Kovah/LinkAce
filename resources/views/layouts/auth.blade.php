<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.header')
</head>
<body class="auth">
<div id="app">

    @include('partials.nav')

    <main class="main container">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

</div>
</body>
</html>
