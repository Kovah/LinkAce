<div class="card mt-5">
    <div class="card-header">
        @lang('settings.cron_token')
    </div>
    <div class="cron-token card-body"
        data-confirm-message="@lang('settings.cron_token_generate_confirm')"
        data-cron-url-base="{{ url('cron/') }}/">

        <p>@lang('settings.cron_token_help')</p>

        <div class="input-group mb-3">
            <input type="text" class="cron-token-input form-control" value="{{ systemsettings('cron_token') }}"
                readonly aria-readonly="true" aria-label="@lang('settings.cron_token_generate')"
                aria-describedby="cron-token-generate">
            <button class="cron-token-generate btn btn-outline-danger" type="button">
                <x-icon.recycle class="me-1"/> @lang('settings.cron_token_generate')
            </button>
        </div>

        <p class="cron-token-generate-failure small text-danger" style="display:none">
            @lang('settings.cron_token_generate_failure')
        </p>

        <p class="small text-warning">@lang('settings.cron_token_generate_info')</p>

        <p class="{{ systemsettings('cron_token') ? '' : 'd-none' }}">
            @lang('settings.cron_token_url', [
                'route' => route('cron', ['token' => systemsettings('cron_token') ?: 'no-token-configured'])
            ])
        </p>

    </div>
</div>
