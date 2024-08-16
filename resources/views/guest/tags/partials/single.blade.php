<tr class="single-tag">
    <td>
        <a href="{{ route('guest.tags.show', [$tag]) }}" class="title">
            <x-models.name-with-user :model="$tag"/>
        </a>
    </td>
    <td>
        {{ $tag->links_count }}
    </td>
</tr>
