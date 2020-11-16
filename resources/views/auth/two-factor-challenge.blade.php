@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    @lang('auth.two_factor')
                </div>
                <div class="card-body">

                    <p>@lang('auth.two_factor_check')</p>

                    <form method="POST" action="{{ url('/two-factor-challenge') }}">
                        @csrf

                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <x-icon.shield/>
                                    </div>
                                </div>
                                <input type="text" name="code" id="code" class="form-control"
                                    autocomplete="one-time-code" inputmode="numeric" autofocus
                                    placeholder="@lang('placeholder.two_factor_otp')"
                                    aria-label="@lang('placeholder.two_factor_otp')">
                            </div>

                            @if ($errors->has('code'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('code') }}
                                </p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary">
                                @lang('linkace.login')
                            </button>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary"
                            data-toggle="collapse" data-target="#recovery-code"
                            aria-expanded="false" aria-controls="recovery-code">
                            @lang('auth.two_factor_with_recovery')
                        </button>

                        <div class="collapse mt-3" id="recovery-code">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <x-icon.shield/>
                                        </div>
                                    </div>
                                    <input type="text" name="recovery_code" id="recovery_code" class="form-control"
                                        autocomplete="one-time-code"
                                        placeholder="@lang('placeholder.two_factor_recovery_code')"
                                        aria-label="@lang('placeholder.two_factor_recovery_code')">
                                </div>

                                @if ($errors->has('code'))
                                    <p class="invalid-feedback" role="alert">
                                        {{ $errors->first('recovery_code') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
