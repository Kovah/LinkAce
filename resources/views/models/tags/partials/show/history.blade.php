<div class="tag-history mt-5">
    <h3 class="h6 mb-2">@lang('linkace.history')</h3>

    <div class="history small text-muted">
        @foreach($history as $entry)
            @if($loop->index === 5 && $loop->count >= 10)
                <a data-bs-toggle="collapse" href="#tag-history" role="button" class="d-inline-block mb-1"
                        aria-expanded="false" aria-controls="tag-history">
                    @lang('linkace.more')
                    <x-icon.caret-down class="fw"/>
                </a>
                <div id="tag-history" class="collapse">
                    @endif
                    <x-history.tag-entry :entry="$entry"/>
                    @endforeach
                    <div>{{ formatDateTime($tag->created_at) }}: @lang('tag.history_created')</div>
                    @if(count($history) >= 10)
                </div>
            @endif
    </div>
</div>
