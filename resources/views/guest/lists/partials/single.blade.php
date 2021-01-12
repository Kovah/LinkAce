<div class="col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-header">
            <div class="d-flex align-items-top">
                <div class="mr-2">
                    @if($list->is_private)
                        <x-icon.lock class="text-muted mr-1" title="@lang('list.private')"/>
                    @endif
                    <a href="{{ route('guest.lists.show', [$list->id]) }}">{{ $list->name }}</a>
                </div>
            </div>
        </div>

        <ul class="list-group list-group-flush h-100">
            <li class="list-group-item h-100 small">
                @if($list->description)
                    <p>{{ $list->description }}</p>
                @endif
                @if($list->links_count > 0)
                    {{ trans_choice('list.number_links', $list->links_count, ['number' => $list->links_count]) }}
                @else
                    <span class="text-muted">@lang('link.no_links')</span>
                @endif
            </li>
        </ul>
    </div>

</div>
