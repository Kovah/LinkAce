<div class="card">
    <div class="card-header">
        @lang('settings.update_check')
    </div>
    <div class="card-body small" >
        <p>@lang('linkace.version', ['version' => $linkaceVersion])</p>
        <x-update-check/>
    </div>
    <div class="card-body text-success">
        <x-icon.info class="me-1"/> <strong>LinkAce 2 is available!</strong> <a href="https://www.linkace.org/docs/v2/upgrade/from-v1/">Upgrade now</a>
    </div>
    <div class="card-body text-danger">
        <x-icon.info class="me-1"/> Please note that LinkAce 1 is now deprecated and will only receive security-related patches until the end of 2025.
    </div>
</div>
