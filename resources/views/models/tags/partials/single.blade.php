<tr>
    <td>
        <a href="{{ route('tags.show', [$tag->id]) }}">
            {{ $tag->name }}
        </a>
    </td>
    <td>
        {{ $tag->links->count() }}
    </td>
    <td class="text-right">
        <div class="btn-group btn-group-xs">
            <a href="{{ route('tags.edit', [$tag->id]) }}" class="btn btn-outline-secondary">
                <i class="fas fa-edit"></i>
            </a>
            <a href="#" title=" @lang('tag.delete')" class="btn btn-outline-secondary"
                onclick="event.preventDefault();document.getElementById('tag-delete-{{ $tag->id }}').submit();">
                <i class="fas fa-trash-alt"></i>
            </a>
        </div>

        <form id="tag-delete-{{ $tag->id }}" method="POST" style="display: none;"
            action="{{ route('tags.destroy', [$tag->id]) }}">
            @method('DELETE')
            @csrf
            <input type="hidden" name="tag_id" value="{{ $tag->id }}">
        </form>
    </td>
</tr>
