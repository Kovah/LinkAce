@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    @lang('linkace.reset_password')
                </div>
                <div class="card-body">

                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}"
                        aria-label="@lang('linkace.reset_password')">
                        @csrf

                        <div class="form-group">
                            <label for="email">@lang('linkace.email')</label>
                            <div class="control">
                                <input name="email" id="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    type="email" placeholder="@lang('placeholder.email')" value="{{ old('email') }}"
                                    required autofocus>
                            </div>
                            @if ($errors->has('email'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('email') }}
                                </p>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">
                            @lang('linkace.send_reset_email')
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
