@extends('layouts.auth')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            @if(env('APP_DEMO', false))
                <div class="alert alert-info small">@lang('linkace.demo_login_hint')</div>
            @endif
            @include('partials.alerts')
            @include('auth.login-form')
        </div>
    </div>

@endsection
