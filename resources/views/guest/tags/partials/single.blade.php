<tr>
    <td>
        <a href="{{ route('guest.tags.show', [$tag->id]) }}">
            {{ $tag->name }}
        </a>
    </td>
    <td>
        {{ $tag->links_count }}
    </td>
</tr>
