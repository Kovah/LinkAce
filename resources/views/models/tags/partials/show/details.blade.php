<div class="tag-details card">
    <header class="card-header d-flex align-items-center">
            <span class="me-3">
                <x-models.visibility-badge :model="$tag" class="d-inline-block me-1"/>
                @lang('tag.tag')
            </span>
        <div class="ms-auto">
            <a href="{{ route('tags.edit', ['tag' => $tag]) }}" class="btn btn-sm btn-primary"
                    aria-label="@lang('tag.edit')">
                <x-icon.edit class="me-2"/>
                @lang('linkace.edit')
            </a>
            <a onclick="event.preventDefault();document.getElementById('tag-delete-{{ $tag->id }}').submit();"
                    class="btn btn-sm btn-outline-danger" aria-label="@choice('tag.delete', 1)">
                <x-icon.trash class="me-2"/>
                @lang('linkace.delete')
            </a>
        </div>
        <form id="tag-delete-{{ $tag->id }}" method="POST" style="display: none;"
                action="{{ route('tags.destroy', ['tag' => $tag]) }}">
            @method('DELETE')
            @csrf
            <input type="hidden" name="tag_id" value="{{ $tag->id }}">
        </form>
    </header>
    <div class="card-body">

        <h2 class="tag-title mb-0">{{ $tag->name }}</h2>

        <div class="mt-2 small">
            @lang('linkace.added_by'):
            <x-models.author :model="$tag"/>
        </div>

    </div>
</div>
