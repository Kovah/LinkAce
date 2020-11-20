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
                        <x-icon.shield class="mr-2"/> @lang('settings.two_factor_enable')
                    </button>

                </form>
            @else

                <form action="{{ url('/user/two-factor-authentication') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-danger">
                        <x-icon.shield class="mr-2"/> @lang('settings.two_factor_disable')
                    </button>

                </form>

                @if(session('status') === 'two-factor-authentication-enabled')

                    <p class="mt-5 mb-4">@lang('settings.two_factor_setup_app')</p>

                    <div class="mb-4">
                        {!! $user->twoFactorQrCodeSvg() !!}
                    </div>

                    <details>
                        <summary class="text-muted small">@lang('settings.two_factor_setup_url')</summary>
                        <code>{{ $user->twoFactorQrCodeUrl() }}</code>
                    </details>

                @endif

                <div class="mt-5 alert alert-warning">@lang('settings.two_factor_recovery_codes')</div>

                <div class="row">
                    <div class="col">
                        <details>
                            <summary>@lang('settings.two_factor_recovery_codes_view')</summary>
                            @foreach (json_decode(decrypt($user->two_factor_recovery_codes), true) as $code)
                                <code>{{ $code }}</code><br>
                            @endforeach
                        </details>
                    </div>

                    <div class="col text-right">
                        <form action="{{ url('/user/two-factor-recovery-codes') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <x-icon.shield class="mr-2"/> @lang('settings.two_factor_regenerate_recovery_codes')
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endif
