<tr>
    <td>
        <div>
            <a href="{{ route('links.show', [$link->id]) }}">
                {{ $link->title }}
            </a>
            @if($link->is_private)
                <span>
                <x-icon.lock class="mr-1" title="@lang('link.private')"/>
                <span class="sr-only">@lang('link.private')</span>
            </span>
            @endif
        </div>
        @if($link->tags->count() > 0)
            <div class="mt-1">
                <label class="small mb-0">@lang('tag.tags'):</label>
                @foreach($link->tags as $tag)
                    <a href="{{ route('tags.show', [$tag->id]) }}" class="badge badge-light">
                        {{ $tag->name }}
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
    <td class="text-muted">
        <small>{!! $link->addedAt() !!}</small>
    </td>
    @if(!isset($hide_edit))
        <td class="text-right">
            <div class="btn-group btn-group-xs">
                <a href="{{ route('links.edit', [$link->id]) }}" class="btn btn-outline-secondary">
                    <x-icon.edit/>
                    <span class="sr-only">@lang('link.edit')</span>
                </a>
                <a href="#" title=" @lang('link.delete')" class="btn btn-outline-secondary"
                    onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();">
                    <x-icon.trash/>
                    <span class="sr-only">@lang('link.delete')</span>
                </a>
            </div>
            <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                action="{{ route('links.destroy', [$link->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="link_id" value="{{ $link->id }}">
            </form>
        </td>
    @endif
</tr>
