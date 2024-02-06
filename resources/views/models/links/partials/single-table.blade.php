<tr>
    <td>
        <div>
            <a href="{{ route('links.show', [$link]) }}">
                {{ $link->title }}
            </a>
            <x-models.visibility-badge :model="$link" class="d-inline-block"/>
        </div>
        @if($link->tags->count() > 0)
            <div class="mt-1">
                @foreach($link->tags as $tag)
                    <a href="{{ route('tags.show', ['tag' => $tag]) }}" class="btn btn-xs btn-light">
                        <x-models.name-with-user :model="$tag"/>
                    </a>
                @endforeach
            </div>
        @endif
    </td>
    <td>
        <a href="{{ $link->url }}" {!! linkTarget() !!}>
            {{ $link->shortUrl() }}
        </a>
    </td>
    <td class="text-pale">
        <small>{!! $link->addedAt() !!}</small>
    </td>
    @if(!isset($hide_edit))
        <td class="py-1 text-end">
            <div class="btn-group btn-group-sm">
                @auth()
                    <a href="{{ route('links.edit', [$link]) }}" class="btn btn-link">
                        <x-icon.edit/>
                        <span class="visually-hidden">@lang('link.edit')</span>
                    </a>
                @endauth
                <button type="submit" form="link-delete-{{ $link->id }}" title="@lang('link.delete')"
                    class="btn btn-link">
                    <x-icon.trash/>
                    <span class="visually-hidden">@lang('link.delete')</span>
                </button>
            </div>
            <form id="link-delete-{{ $link->id }}" method="POST" class="d-none"
                action="{{ route('links.destroy', [$link]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="redirect_back" value="1">
                <input type="hidden" name="link_id" value="{{ $link->id }}">
            </form>
        </td>
    @endif
</tr>
