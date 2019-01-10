<tr>
    <td>
        <a href="{{ route('guest.tags.show', [$tag->id]) }}">
            {{ $tag->name }}
        </a>
    </td>
    <td>
        {{ $tag->links->count() }}
    </td>
    <td>
        {{ $tag->user->name }}
    </td>
</tr>
