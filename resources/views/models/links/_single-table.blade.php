<tr>
    <td>
        <a href="{{ route('links.show', [$link->id]) }}">
            {{ $link->title }}
        </a>
    </td>
    <td>
        <a href="{{ $link->url }}" target="_blank">
            {{ $link->url }}
        </a>
    </td>
    <td class="text-muted">
        <small>{!! $link->addedAt() !!}</small>
    </td>
    @if(!isset($hide_edit))
        <td>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('links.edit', [$link->id]) }}" class="btn btn-outline-primary">
                    <i class="fa fa-pencil"></i>
                </a>
                <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                    title=" @lang('link.delete')" class="btn btn-outline-danger">
                    <i class="fa fa-trash"></i>
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
