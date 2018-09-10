<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LinkAce') }}</title>

    <link href="{{ asset('assets/app.css') }}" rel="stylesheet">
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
                    <i class="fa fa-unlock fa-mr"></i> @lang('linkace.login')
                </a>

            </div>
        </div>
    </main>

</div>
</body>
</html>
