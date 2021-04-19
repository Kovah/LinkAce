<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.header')
</head>
<body>
<div id="app">

    <main class="main container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 text-center">

                <div class="mb-5 display-2 text-center text-primary">
                    <x-icon.linkace/>
                </div>

                <a href="{{ route('login') }}" class="btn btn-lg btn-primary">
                    <x-icon.unlock class="mr-2"/> @lang('linkace.login')
                </a>

            </div>
        </div>
    </main>

</div>
</body>
</html>
