<tr>
    <td>
        <a href="{{ route('links.show', [$link->id]) }}">
            {{ $link->title }}
        </a>
        @if($link->is_private)
            <x-icon.lock class="text-muted" title="@lang('link.private')"/>
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
                </a>
                <a href="#" title=" @lang('link.delete')" class="btn btn-outline-secondary"
                    onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();">
                    <x-icon.trash/>
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
