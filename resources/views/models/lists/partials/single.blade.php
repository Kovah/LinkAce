<div class="col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-header">
            <div class="d-flex align-items-top">
                <div class="me-2">
                    @if($list->is_private)
                        <span>
                            <x-icon.lock class="me-1" title="@lang('list.private')"/>
                            <span class="visually-hidden">@lang('list.private')</span>
                        </span>
                    @endif
                    <a href="{{ route('lists.show', [$list->id]) }}" class="text-decoration-none">{{ $list->name }}</a>
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

        <div class="card-footer">
            <div class="btn-group w-100">
                <a href="{{ route('lists.edit', [$list->id]) }}" class="card-link">
                    <x-icon.edit/>
                    <span class="visually-hidden">@lang('list.edit')</span>
                </a>
                <a href="#" title="@lang('list.delete')" class="card-link cursor-pointer"
                    onclick="event.preventDefault();document.getElementById('list-delete-{{ $list->id }}').submit();">
                    <x-icon.trash/>
                    <span class="visually-hidden">@lang('list.delete')</span>
                </a>
            </div>

            <form id="list-delete-{{ $list->id }}" method="POST" style="display: none;"
                action="{{ route('lists.destroy', [$list->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="list_id" value="{{ $list->id }}">
            </form>
        </div>
    </div>

</div>
