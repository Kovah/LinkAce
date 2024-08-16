<div class="single-list col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-body">
            <div class="d-flex">
                <x-models.visibility-badge :model="$list" class="d-inline-block me-2"/>
                <a href="{{ route('lists.show', ['list' => $list]) }}" class="title">
                    <x-models.name-with-user :model="$list"/>
                </a>
            </div>
            @if($list->description)
                <div class="description small mt-2">{{ $list->description }}</div>
            @endif
        </div>

        <div class="meta d-flex align-items-center">
            <div class="text-xs text-pale me-3 ps-3 text-condensed">
                @if($list->links_count > 0)
                    {{ trans_choice('list.number_links', $list->links_count, ['number' => $list->links_count]) }}
                @else
                    @lang('link.no_links')
                @endif
            </div>
            <div class="btn-group ms-auto me-1">
                <a href="{{ route('lists.edit', ['list' => $list]) }}" class="btn btn-xs btn-link text-condensed">
                    @lang('linkace.edit')
                </a>
                <button type="submit" form="list-delete-{{ $list->id }}" class="btn btn-xs btn-link text-condensed">
                    @lang('linkace.delete')
                </button>
            </div>
            <input type="checkbox" aria-label="@lang('list.bulk_edit_add')" class="bulk-edit-model form-check me-2"
                data-id="{{ $list->id }}">

            <form id="list-delete-{{ $list->id }}" method="POST" style="display: none;"
                action="{{ route('lists.destroy', ['list' => $list]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="redirect_back" value="1">
                <input type="hidden" name="list_id" value="{{ $list->id }}">
            </form>
        </div>
    </div>

</div>
