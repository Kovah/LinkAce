@extends('layouts.app')

@section('content')

    <div class="columns is-centered">
        <div class="column is-half">

            <div class="card">
                <div class="card-content">

                    <form method="POST" action="{{ route('password.request') }}"
                        aria-label="@lang('linkace.reset_password')">
                        @csrf

                        <div class="field">
                            <label class="label" for="email">@lang('linkace.email')</label>
                            <div class="control">
                                <input name="email" id="email"
                                    class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                                    type="email" placeholder="@lang('linkace.email')"
                                    value="{{ $email ?? old('email') }}"
                                    required autofocus>
                            </div>
                            @if ($errors->has('email'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('email') }}
                                </p>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label" for="password">@lang('linkace.password')</label>
                            <div class="control">
                                <input name="password" id="password"
                                    class="input{{ $errors->has('password') ? ' is-danger' : '' }}"
                                    type="password" placeholder="@lang('linkace.password')"
                                    required>
                            </div>
                            @if ($errors->has('password'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('password') }}
                                </p>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label" for="password-confirm">@lang('linkace.password_confirm')</label>
                            <div class="control">
                                <input name="password_confirmation" id="password-confirm"
                                    class="input{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}"
                                    type="email" placeholder="@lang('linkace.password_confirm')"
                                    required>
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('password_confirmation') }}
                                </p>
                            @endif
                        </div>

                        <br>

                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-primary">
                                    <i class="fa fa-save fa-mr"></i> @lang('linkace.reset_password')
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
