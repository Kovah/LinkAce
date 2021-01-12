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

                <img src="{{ asset('assets/img/linkace_logo.svg') }}" alt="@lang('linkace.linkace')"
                    width="400" height="147" class="my-5 px-3 px-sm-5 mw-100 h-auto">

                <a href="{{ route('login') }}" class="btn btn-lg btn-primary">
                    <x-icon.unlock class="mr-2"/> @lang('linkace.login')
                </a>

            </div>
        </div>
    </main>

</div>
</body>
</html>
