<tr>
    <td>
        <a href="{{ route('tags.show', [$tag->id]) }}">
            {{ $tag->name }}
        </a>
    </td>
    <td>
        {{ $tag->links->count() }}
    </td>
    <td>
        <div class="btn-group btn-group-sm">
            <a href="{{ route('tags.edit', [$tag->id]) }}" class="btn btn-outline-primary">
                <i class="fa fa-pencil"></i>
            </a>
            <a onclick="event.preventDefault();document.getElementById('tag-delete-{{ $tag->id }}').submit();"
                title=" @lang('tag.delete')" class="btn btn-outline-danger">
                <i class="fa fa-trash"></i>
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
