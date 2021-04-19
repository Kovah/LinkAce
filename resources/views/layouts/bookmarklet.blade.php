<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.header')
</head>
<body class="bookmarklet">
<div id="app">

    <main class="main container">
        <div class="mb-4 text-center h2">
            <a class="bookmarklet-logo d-inline-block"
                href="{{ auth()->guest() ? route('front') : route('dashboard') }}">
                <x-icon.linkace/>
            </a>
        </div>

        @include('partials.alerts')
        @yield('content')
    </main>

</div>
</body>
</html>
