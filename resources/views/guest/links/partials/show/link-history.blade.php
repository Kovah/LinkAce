<div class="link-history mt-5">
    <h3 class="h6 mb-2">@lang('link.history')</h3>

    <div class="history small text-pale">
        @foreach($history as $entry)
            @if($loop->index === 5 && $loop->count >= 10)
                <a data-bs-toggle="collapse" href="#link-history" role="button" class="d-inline-block mb-1"
                    aria-expanded="false" aria-controls="link-history">
                    @lang('linkace.more')
                    <x-icon.caret-down class="fw"/>
                </a>
                <div id="link-history" class="collapse">
                    @endif
                    <x-history.link-entry :entry="$entry"/>
                    @endforeach
                    <div>{{ formatDateTime($link->created_at) }}: @lang('link.history_created')</div>
                    @if(count($history) >= 10)
                </div>
            @endif
    </div>
</div>
