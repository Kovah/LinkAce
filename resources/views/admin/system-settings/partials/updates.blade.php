<div class="card">
    <div class="card-header">
        @lang('settings.update_check')
    </div>
    <div class="card-body small" >
        <p>@lang('linkace.version', ['version' => $linkaceVersion])</p>
        <x-update-check/>
    </div>
</div>
