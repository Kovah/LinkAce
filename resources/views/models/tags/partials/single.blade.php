<tr>
    <td>
        @if($tag->is_private)
            <span>
                <x-icon.lock class="mr-1" title="@lang('tag.private')"/>
                <span class="sr-only">@lang('tag.private')</span>
            </span>
        @endif
        <a href="{{ route('tags.show', [$tag->id]) }}">
            {{ $tag->name }}
        </a>
    </td>
    <td>
        {{ $tag->links_count }}
    </td>
    <td class="text-right">
        <div class="btn-group btn-group-xs">
            <a href="{{ route('tags.edit', [$tag->id]) }}" class="btn btn-outline-secondary">
                <x-icon.edit class="fw"/>
                <span class="sr-only">@lang('tag.edit')</span>
            </a>
            <a href="#" title=" @lang('tag.delete')" class="btn btn-outline-secondary"
                onclick="event.preventDefault();document.getElementById('tag-delete-{{ $tag->id }}').submit();">
                <x-icon.trash class="fw"/>
                <span class="sr-only">@lang('tag.delete')</span>
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
