<div class="card">
    <div class="card-header">
        @lang('settings.update_check')
    </div>
    <div class="card-body small" >
        <p>@lang('linkace.version', ['version' => $linkaceVersion])</p>
        <x-update-check/>
    </div>
    <div class="card-body text-danger">
        <x-icon.info class="me-1"/> Please note that the LinkAce Docker image will be renamed with the release of LinkAce 2! <a href="https://github.com/Kovah/LinkAce/issues/502">Read more</a>
    </div>
</div>
