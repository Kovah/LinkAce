<tr>
    <td>
        @if(isset($is_child))
        &rightarrow;
        @endif
        <a href="{{ route('guest.categories.show', [$category->id]) }}">
            {{ $category->name }}
        </a>
    </td>
    <td>
        <small>{{ $category->description }}</small>
    </td>
    <td class="text-right">
        <small>{{ $category->links->count() }}</small>
    </td>
    <td>
        {{ $category->user->name }}
    </td>
</tr>
