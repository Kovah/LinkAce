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

                <img src="{{ asset('assets/img/logo_linkace.svg') }}" alt="@lang('linkace.linkace')"
                    width="400" height="147" class="mt-5 mb-5">

                <br>

                <a href="{{ route('login') }}" class="btn btn-lg btn-primary">
                    <i class="fas fa-unlock mr-2"></i> @lang('linkace.login')
                </a>

            </div>
        </div>
    </main>

</div>
</body>
</html>
