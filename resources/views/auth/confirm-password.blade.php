@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            @if(env('APP_DEMO', false))
                <div class="alert alert-info small">@lang('linkace.demo_login_hint')</div>
            @endif

            <div class="card">
                <div class="card-header">
                    @lang('auth.confirm_title')
                </div>
                <div class="card-body">

                    <p>@lang('auth.confirm')</p>

                    <form method="POST" action="{{ url('/user/confirm-password') }}"
                        aria-label="@lang('auth.confirm_title')">
                        @csrf

                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <x-icon.lock/>
                                    </div>
                                </div>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="@lang('placeholder.password')" aria-label="@lang('linkace.password')">
                            </div>
                            @if ($errors->has('password'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('password') }}
                                </p>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <x-icon.save class="mr-2"/> @lang('auth.confirm_action')
                        </button>

                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
