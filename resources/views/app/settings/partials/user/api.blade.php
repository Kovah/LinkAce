<div class="card mt-5">
    <div class="card-header">
        @lang('settings.api_token')
    </div>
    <div class="api-token card-body">

        <p>@lang('settings.api_token_help')</p>

        <div class="input-group mb-3">
            <input type="text" class="api-token-input form-control" value="{{ auth()->user()->api_token ?? '' }}"
                readonly aria-readonly="true" aria-label="@lang('settings.api_token_generate')"
                aria-describedby="api-token-generate">
            <div class="input-group-append">
                <button class="api-token-generate btn btn-outline-danger" type="button">
                    <x-icon.recycle class="mr-1"/> @lang('settings.api_token_generate')
                </button>
            </div>
        </div>

        <p class="api-token-generate-failure small text-danger d-none">
            @lang('settings.api_token_generate_failure')
        </p>

        <p class="small text-warning">@lang('settings.api_token_generate_info')</p>

    </div>
</div>
