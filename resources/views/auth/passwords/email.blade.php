@extends('layouts.app')

@section('content')

    <div class="columns is-centered">
        <div class="column is-half">

            <div class="card">
                <div class="card-content">

                    <form method="POST" action="{{ route('password.email') }}" aria-label="@lang('linkace.reset_password')">
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

                        <br>

                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-primary">
                                    <i class="fa fa-envelope-open fa-mr"></i> @lang('linkace.send_reset_email')
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
