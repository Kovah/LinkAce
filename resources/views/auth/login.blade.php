@extends('layouts.app')

@section('content')

    <div class="columns is-centered">
        <div class="column is-half">

            <div class="card">
                <div class="card-content">

                    <form method="POST" action="{{ route('login') }}" aria-label="@lang('linkace.login')">
                        @csrf

                        <div class="field">
                            <label class="label" for="email">@lang('linkace.email')</label>
                            <div class="control">
                                <input name="email" id="email" class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                                    type="email" placeholder="@lang('linkace.email')" value="{{ old('email') }}"
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
                                    type="password" placeholder="@lang('linkace.password')" required>
                            </div>
                            @if ($errors->has('password'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('password') }}
                                </p>
                            @endif
                        </div>

                        <div class="field">
                            <div class="control">
                                <label class="checkbox">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    @lang('linkace.remember_me')
                                </label>
                            </div>
                        </div>

                        <br>

                        <div class="field is-grouped">
                            <div class="control">
                                <button type="submit" class="button is-primary">
                                    <i class="fa fa-unlock fa-mr"></i> @lang('linkace.login')
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

            <br>

            <small class="has-text-grey">
                {!! trans('linkace.forgot_password_link', ['reset_url' => route('password.request')]) !!}
            </small>

        </div>
    </div>

@endsection
