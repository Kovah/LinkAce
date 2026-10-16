@if(!env('APP_DEMO', false))
    <div class="card mt-5">
        <div class="card-header">
            @lang('settings.two_factor_auth')
        </div>
        <div class="card-body">

            @if(!$user->two_factor_secret)
                <form action="{{ url('/user/two-factor-authentication') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-primary">
                        <x-icon.shield class="me-2"/> @lang('settings.two_factor_enable')
                    </button>

                </form>
            @else

                <form action="{{ url('/user/two-factor-authentication') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-danger">
                        <x-icon.shield class="me-2"/> @lang('settings.two_factor_disable')
                    </button>

                </form>

                @if(session('status') === 'two-factor-authentication-enabled')

                    <p class="mt-5 mb-4">@lang('settings.two_factor_setup_app')</p>

                    <div class="mb-4">
                        <div class="d-inline-block border border-5 border-white">
                            {!! $user->twoFactorQrCodeSvg() !!}
                        </div>
                    </div>

                    <details>
                        <summary class="text-pale small">@lang('settings.two_factor_setup_url')</summary>
                        <code>{{ $user->twoFactorQrCodeUrl() }}</code>
                    </details>

                @endif

                <div class="mt-5 alert alert-warning">@lang('settings.two_factor_recovery_codes')</div>

                <div class="row">
                    <div class="col recovery-codes" data-failure-message="@lang('settings.two_factor_recovery_codes_failure')">
                        <button type="button" class="btn btn-sm btn-outline-primary recovery-codes-show">
                            @lang('settings.two_factor_recovery_codes_view')
                        </button>

                        <div class="recovery-codes-form d-none mt-3">
                            <label class="form-label" for="recovery_codes_password">
                                @lang('settings.two_factor_recovery_codes_password')
                            </label>
                            <div class="input-group">
                                <input type="password" id="recovery_codes_password" autocomplete="current-password"
                                    class="form-control recovery-codes-password">
                                <button type="button" class="btn btn-primary recovery-codes-submit">
                                    @lang('linkace.show')
                                </button>
                            </div>
                            <p class="invalid-feedback d-block d-none recovery-codes-error" role="alert"></p>
                        </div>

                        <div class="recovery-codes-output d-none mt-3"></div>
                    </div>

                    <div class="col text-end">
                        <form action="{{ url('/user/two-factor-recovery-codes') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <x-icon.shield class="me-2"/> @lang('settings.two_factor_regenerate_recovery_codes')
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endif
