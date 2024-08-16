@extends('layouts.app')

@section('content')

    <div class="register row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    @lang('auth.register')
                </div>
                <div class="card-body">
                    <p>@lang('auth.register_welcome')</p>

                    <form method="POST" action="{{ route('auth.register') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $invitation->token }}">

                        <div class="mb-4">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <x-icon.envelope/>
                                </div>
                                <input type="email" name="email" id="email" class="form-control" readonly
                                    value="{{ $invitation->email }}" aria-label="@lang('user.email')">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <x-icon.info/>
                                </div>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                                    aria-label="@lang('user.username')" placeholder="@lang('user.username')">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <x-icon.lock/>
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

                        <div class="mb-4">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <x-icon.lock/>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" placeholder="@lang('placeholder.password_confirmed')"
                                    aria-label="@lang('linkace.password_confirm')">
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('password_confirmation') }}
                                </p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                @lang('auth.register')
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
