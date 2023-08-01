<div class="card">
    <div class="card-header">
        @lang('linkace.login')
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('login') }}" aria-label="@lang('linkace.login')">
            @csrf

            <div class="mb-4">
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <x-icon.envelope/>
                    </div>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ config('app.demo') ? 'linkace@example.com' : old('email') }}"
                        placeholder="@lang('placeholder.email')" aria-label="@lang('linkace.email')"
                        required autofocus>
                </div>

                @if ($errors->has('email'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            <div class="mb-4">
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <x-icon.lock/>
                    </div>
                    <input type="password" name="password" id="password" class="form-control"
                        @if(config('app.demo')) value="demopassword" @endif
                    placeholder="@lang('placeholder.password')" aria-label="@lang('linkace.password')">
                </div>
                @if ($errors->has('password'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('password') }}
                    </p>
                @endif
            </div>

            <div class="d-flex mt-4">
                <div class="form-check ms-auto pt-1">
                    <input type="hidden" name="remember_me" value="0">
                    <input type="checkbox" class="form-check-input" id="remember_me"
                        @if(old('remember_me')) checked @endif>

                    <label class="form-check-label" for="remember_me">
                        @lang('linkace.remember_me')
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block ms-3">
                    @lang('linkace.login')
                </button>
            </div>

        </form>
    </div>
</div>

<div class="text-center text-pale mt-3 small">
    {!! trans('linkace.forgot_password_link', ['reset_url' => route('password.request')]) !!}
</div>
