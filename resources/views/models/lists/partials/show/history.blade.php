<div class="list-history mt-5">
    <h3 class="h6 mb-2">@lang('linkace.history')</h3>

    <div class="history small text-muted">
        @foreach($history as $entry)
            @if($loop->index === 5 && $loop->count >= 10)
                <a data-bs-toggle="collapse" href="#list-history" role="button" class="d-inline-block mb-1"
                    aria-expanded="false" aria-controls="list-history">
                    @lang('linkace.more')
                    <x-icon.caret-down class="fw"/>
                </a>
                <div id="list-history" class="collapse">
                    @endif
                    <x-history.list-entry :entry="$entry"/>
                    @endforeach
                    <div>{{ formatDateTime($list->created_at) }}: @lang('list.history_created')</div>
                    @if(count($history) >= 10)
                </div>
            @endif
    </div>
</div>
