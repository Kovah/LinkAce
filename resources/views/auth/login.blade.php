@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            @if(env('APP_DEMO', false))
                <div class="alert alert-info small">@lang('linkace.demo_login_hint')</div>
            @endif

            <div class="card">
                <div class="card-header">
                    @lang('linkace.login')
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" aria-label="@lang('linkace.login')">
                        @csrf

                        <div class="mb-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <x-icon.envelope/>
                                    </div>
                                </div>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ config('app.demo') ? 'linkace@example.com' : old('email') }}"
                                    placeholder="@lang('placeholder.email')" aria-label="@lang('linkace.email')"
                                    required autofocus>
                            </div>

                            @if ($errors->has('email'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('email') }}
                                </p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <x-icon.lock/>
                                    </div>
                                </div>
                                <input type="password" name="password" id="password" class="form-control"
                                    @if(config('app.demo')) value="demopassword" @endif
                                placeholder="@lang('placeholder.password')" aria-label="@lang('linkace.password')">
                            </div>
                            @if ($errors->has('password'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('password') }}
                                </p>
                            @endif
                        </div>

                        <div class="row mt-4">
                            <div class="col-8">

                                <div class="form-check pt-1">
                                    <input type="hidden" name="remember_me" value="0">
                                    <input type="checkbox" class="form-check-input" id="remember_me"
                                        @if(old('remember_me')) checked @endif>

                                    <label class="form-check-label" for="remember_me">
                                        @lang('linkace.remember_me')
                                    </label>
                                </div>

                            </div>
                            <div class="col-4">

                                <button type="submit" class="btn btn-primary btn-block">
                                    @lang('linkace.login')
                                </button>

                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <div class="text-muted mt-2 small">
                {!! trans('linkace.forgot_password_link', ['reset_url' => route('password.request')]) !!}
            </div>

        </div>
    </div>

@endsection
