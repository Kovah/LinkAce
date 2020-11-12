@extends('layouts.setup')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    @lang('setup.account_setup')
                </div>
                <div class="card-body">

                    <p>@lang('setup.account_setup.intro')</p>

                    @include('partials.alerts')

                    <form action="{{ route('setup.save-account') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="name">
                                @lang('setup.account_setup.name')
                            </label>
                            <input type="text" name="name" id="name"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                placeholder="@lang('placeholder.username')" aria-label="@lang('linkace.username')"
                                value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email">
                                @lang('setup.account_setup.email')
                            </label>
                            <input type="email" name="email" id="email"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="@lang('placeholder.email')" aria-label="@lang('linkace.email')"
                                value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('email') }}
                                </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">
                                @lang('setup.account_setup.password')
                            </label>
                            <input type="password" name="password" id="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                value="{{ old('password') }}" aria-label="@lang('linkace.password')">
                            @if ($errors->has('password'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('password') }}
                                </p>
                            @else
                                <p class="form-text text-muted small">
                                    @lang('setup.account_setup.password_requirements')
                                </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">
                                @lang('setup.account_setup.password_confirmed')
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                value="{{ old('password_confirmation') }}" aria-label="@lang('linkace.password_confirmed')">
                            @if ($errors->has('password_confirmation'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('password_confirmation') }}
                                </p>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">@lang('setup.account_setup.create')</button>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
