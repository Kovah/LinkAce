<div class="col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-body">
            <div class="d-flex">
                @if($list->is_private)
                    <span>
                        <x-icon.lock class="me-2" title="@lang('list.private')"/>
                        <span class="visually-hidden">@lang('list.private')</span>
                    </span>
                @endif
                <a href="{{ route('lists.show', [$list->id]) }}">{{ $list->name }}</a>
            </div>
            @if($list->description)
                <div class="small mt-2">{{ $list->description }}</div>
            @endif
        </div>

        <div class="d-flex align-items-center">
            <div class="text-xs text-pale me-3 ps-3">
                @if($list->links_count > 0)
                    {{ trans_choice('list.number_links', $list->links_count, ['number' => $list->links_count]) }}
                @else
                    @lang('link.no_links')
                @endif
            </div>
            <div class="btn-group ms-auto me-2">
                <a href="{{ route('lists.edit', [$list->id]) }}" class="btn btn-sm btn-link">
                    <x-icon.edit/>
                    <span class="visually-hidden">@lang('list.edit')</span>
                </a>
                <button type="submit" form="list-delete-{{ $list->id }}" title="@lang('list.delete')"
                    class="btn btn-sm btn-link">
                    <x-icon.trash/>
                    <span class="visually-hidden">@lang('list.delete')</span>
                </button>
            </div>

            <form id="list-delete-{{ $list->id }}" method="POST" style="display: none;"
                action="{{ route('lists.destroy', [$list->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="redirect_back" value="1">
                <input type="hidden" name="list_id" value="{{ $list->id }}">
            </form>
        </div>
    </div>

</div>
