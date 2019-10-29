<tr>
    <td>
        <a href="{{ route('guest.lists.show', [$list->id]) }}">
            {{ $list->name }}
        </a>
    </td>
    <td>
        <small>{{ $list->description }}</small>
    </td>
    <td class="text-right">
        <small>{{ $list->links->count() }}</small>
    </td>
</tr>
