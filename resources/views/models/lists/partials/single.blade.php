<div class="col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-header">
            <div class="d-flex align-items-top">
                <div class="mr-2">
                    @if($list->is_private)
                        <i class="fas fa-lock text-muted mr-1" title="@lang('list.private')"></i>
                    @endif
                    <a href="{{ route('lists.show', [$list->id]) }}">{{ $list->name }}</a>
                </div>
            </div>
        </div>

        <ul class="list-group list-group-flush h-100">
            <li class="list-group-item h-100 small">
                @if($list->description)
                    <p>{{ $list->description }}</p>
                @endif
                @if($list->links->count() > 0)
                    {{ trans_choice('list.number_links', $list->links->count(), ['number' => $list->links->count()]) }}
                @else
                    <span class="text-muted">@lang('link.no_links')</span>
                @endif
            </li>
        </ul>

        <div class="card-footer">
            <div class="btn-group w-100">
                <a href="{{ route('lists.edit', [$list->id]) }}" class="card-link">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="#"
                    onclick="event.preventDefault();document.getElementById('list-delete-{{ $list->id }}').submit();"
                    title=" @lang('list.delete')" class="card-link cursor-pointer">
                    <i class="fas fa-trash-alt"></i>
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
