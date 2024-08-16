<div class="list-details card">
    <header class="card-header d-flex align-items-center">
        <span class="me-3">
            <x-models.visibility-badge :model="$list" class="d-inline-block me-1"/>
            @lang('list.list')
        </span>
        <div class="ms-auto">
            <a href="{{ route('lists.edit', ['list' => $list]) }}" class="btn btn-sm btn-primary"
                aria-label="@lang('list.edit')">
                <x-icon.edit class="me-2"/>
                @lang('linkace.edit')
            </a>
            <a onclick="event.preventDefault();document.getElementById('list-delete-{{ $list->id }}').submit();"
                class="btn btn-sm btn-outline-danger" aria-label="@choice('list.delete', 1)">
                <x-icon.trash class="me-2"/>
                @lang('linkace.delete')
            </a>
        </div>
        <form id="list-delete-{{ $list->id }}" method="POST" style="display: none;"
            action="{{ route('lists.destroy', ['list' => $list]) }}">
            @method('DELETE')
            @csrf
            <input type="hidden" name="list_id" value="{{ $list->id }}">
        </form>
    </header>
    <div class="card-body">

        <h2 class="list-title mb-0">{{ $list->name }}</h2>

        <div class="mt-2 small">@lang('linkace.added_by'): <x-models.author :model="$list"/></div>

        @if($list->description)
            <div class="mt-2 mb-0">{!! $list->formatted_description !!}</div>
        @endif

    </div>
</div>
