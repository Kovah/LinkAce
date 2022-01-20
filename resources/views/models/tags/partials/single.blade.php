<tr>
    <td>
        @if($tag->is_private)
            <span>
                <x-icon.lock class="me-1" title="@lang('tag.private')"/>
                <span class="visually-hidden">@lang('tag.private')</span>
            </span>
        @endif
        <a href="{{ route('tags.show', [$tag]) }}">
            {{ $tag->name }}
        </a>
    </td>
    <td>
        {{ $tag->links_count }}
    </td>
    <td class="py-1 text-end">
        <div class="btn-group btn-group-sm">
            <a href="{{ route('tags.edit', [$tag]) }}" class="btn btn-link">
                <x-icon.edit class="fw"/>
                <span class="visually-hidden">@lang('tag.edit')</span>
            </a>
            <button type="submit" form="tag-delete-{{ $tag->id }}" title="@lang('tag.delete')"
                class="btn btn-link">
                <x-icon.trash class="fw"/>
                <span class="visually-hidden">@lang('tag.delete')</span>
            </button>
        </div>

        <form id="tag-delete-{{ $tag->id }}" method="POST" style="display: none;"
            action="{{ route('tags.destroy', [$tag]) }}">
            @method('DELETE')
            @csrf
            <input type="hidden" name="redirect_back" value="1">
            <input type="hidden" name="tag_id" value="{{ $tag->id }}">
        </form>
    </td>
</tr>
