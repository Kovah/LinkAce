<tr>
    <td>
        <a href="{{ route('guest.tags.show', [$tag]) }}" class="text-decoration-none">
            {{ $tag->name }}
        </a>
    </td>
    <td>
        {{ $tag->links_count }}
    </td>
</tr>
