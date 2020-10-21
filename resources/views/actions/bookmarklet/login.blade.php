@extends('layouts.bookmarklet')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('linkace.login')
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}" aria-label="@lang('linkace.login')">
                @csrf

                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <x-icon.envelope class="fw"/>
                            </div>
                        </div>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email') }}"
                            placeholder="@lang('placeholder.email')" aria-label="@lang('linkace.email')" required
                            autofocus>
                    </div>

                    @if ($errors->has('email'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>

                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <x-icon.lock class="fw"/>
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

                <div class="row mt-4">
                    <div class="col-8">

                        <div class="custom-control custom-checkbox pt-1">
                            <input type="hidden" name="remember_me" value="0">
                            <input type="checkbox" class="custom-control-input" id="remember_me"
                                @if(old('remember_me')) checked @endif>

                            <label class="custom-control-label" for="remember_me">
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


@endsection
