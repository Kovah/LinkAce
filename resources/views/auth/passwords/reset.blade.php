@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    @lang('linkace.reset_password')
                </div>
                <div class="card-body">

                    <form method="POST" action="{{ route('password.update') }}"
                        aria-label="@lang('linkace.reset_password')">
                        @csrf

                        <input type="hidden" name="token" value="{{ request()->route('token') }}">

                        <div class="form-group">
                            <label for="email">@lang('linkace.email')</label>
                            <div class="control">
                                <input name="email" id="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    type="email" placeholder="@lang('placeholder.email')"
                                    value="{{ $email ?? old('email') }}"
                                    required autofocus>
                            </div>
                            @if ($errors->has('email'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('email') }}
                                </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">@lang('linkace.password')</label>
                            <div class="control">
                                <input name="password" id="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    type="password" placeholder="@lang('placeholder.password')"
                                    required>
                            </div>
                            @if ($errors->has('password'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('password') }}
                                </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">@lang('linkace.password_confirm')</label>
                            <div class="control">
                                <input name="password_confirmation" id="password-confirm"
                                    class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                    type="password" placeholder="@lang('placeholder.password_confirmed')"
                                    required>
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('password_confirmation') }}
                                </p>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <x-icon.save class="mr-2"/> @lang('linkace.reset_password')
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
