<div class="card">
    <div class="card-header">
        @lang('settings.update_check')
    </div>
    <div class="update-check card-body small" data-current-version="{{ $linkaceVersion }}">

        <p>@lang('linkace.version', ['version' => $linkaceVersion])</p>

        <div class="update-check-running">@lang('settings.update_check_running')</div>
        <div class="update-check-version-found text-success d-none">@lang('settings.update_check_version_found')</div>
        <div class="update-check-success d-none">@lang('settings.update_check_success')</div>
        <div class="update-check-failed text-danger d-none">@lang('settings.update_check_failed')</div>

    </div>
</div>
