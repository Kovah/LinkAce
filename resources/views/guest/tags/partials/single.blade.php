<tr>
    <td>
        <a href="{{ route('guest.tags.show', [$tag]) }}">
            {{ $tag->name }}
        </a>
    </td>
    <td>
        {{ $tag->links_count }}
    </td>
</tr>
