<div class="col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-body">
            <a href="{{ route('guest.lists.show', [$list]) }}">{{ $list->name }}</a>
            @if($list->description)
                <div class="small mt-2">{{ $list->description }}</div>
            @endif
        </div>

        <div class="py-2 px-3">
            <div class="text-xs text-muted">
                @if($list->links_count > 0)
                    {{ trans_choice('list.number_links', $list->links_count, ['number' => $list->links_count]) }}
                @else
                    @lang('link.no_links')
                @endif
            </div>
        </div>
    </div>

</div>
