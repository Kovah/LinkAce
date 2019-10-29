<tr>
    <td>
        <a href="{{ route('lists.show', [$list->id]) }}">
            {{ $list->name }}
        </a>
    </td>
    <td>
        {{ $list->links->count() }}
    </td>
    <td>
        <div class="btn-group btn-group-sm">
            <a href="{{ route('lists.edit', [$list->id]) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit"></i>
            </a>
            <a onclick="event.preventDefault();document.getElementById('list-delete-{{ $list->id }}').submit();"
                title=" @lang('list.delete')" class="btn btn-outline-danger">
                <i class="fas fa-trash"></i>
            </a>
        </div>

        <form id="list-delete-{{ $list->id }}" method="POST" style="display: none;"
            action="{{ route('lists.destroy', [$list->id]) }}">
            @method('DELETE')
            @csrf
            <input type="hidden" name="list_id" value="{{ $list->id }}">
        </form>
    </td>
</tr>
