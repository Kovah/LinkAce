<div {{ $attributes->merge(['class' => 'update-check']) }} data-current-version="{{ \App\Helper\UpdateHelper::currentVersion() }}">
    <div class="update-check-running">@lang('settings.update_check_running')</div>
    <div class="update-check-version-found text-success d-none">@lang('settings.update_check_version_found')</div>
    <div class="update-check-success d-none">@lang('settings.update_check_success')</div>
    <div class="update-check-failed text-danger d-none">@lang('settings.update_check_failed')</div>
</div>
