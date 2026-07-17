@if(config('linkace.search.driver') !== 'database')
<div class="card mt-5">
    <div class="card-header">
        @lang('settings.search_reindex')
    </div>
    <div class="card-body">
        <p>@lang('settings.search_reindex_help')</p>

        <form method="POST" action="{{ route('reindex-search') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                <x-icon.search class="me-2"/> @lang('settings.search_reindex_button')
            </button>
        </form>
    </div>
</div>
@endif
