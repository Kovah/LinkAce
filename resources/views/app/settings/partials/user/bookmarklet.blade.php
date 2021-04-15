<div class="card">
    <div class="card-header">
        @lang('settings.bookmarklet')
    </div>
    <div class="card-body">

        <p>@lang('settings.bookmarklet_help')</p>

        <a href="{{ $bookmarklet_code }}" class="btn btn-primary mb-2">
            <x-icon.bookmark class="mr-2"/> @lang('settings.bookmarklet')
        </a>

        <p class="small">@lang('settings.bookmarklet_button')</p>

    </div>
</div>
