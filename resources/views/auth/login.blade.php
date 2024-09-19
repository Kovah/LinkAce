@extends('layouts.auth')

@section('content')

    <div class="login row justify-content-center">
        <div class="col-12 col-md-8">
            @if(config('app.demo'))
                <div class="alert alert-info small">@lang('linkace.demo_login_hint')</div>
            @endif
            @include('partials.alerts')
            @if(config('auth.sso.regular_login_disabled') !== true)
                @include('auth.login-form')
            @endif
            @if(config('auth.sso.enabled') === true)
                @include('auth.oauth')
            @endif
        </div>
    </div>

@endsection
