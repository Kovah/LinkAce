<tr>
    <td>
        <a href="{{ route('links.show', [$link->id]) }}">
            {{ $link->title }}
        </a>
        @if($link->is_private)
            <i class="fas fa-lock text-muted" title="@lang('link.private')"></i>
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
                    <i class="fas fa-edit"></i>
                </a>
                <a href="#" title=" @lang('link.delete')" class="btn btn-outline-secondary"
                    onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();">
                    <i class="fas fa-trash-alt"></i>
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
