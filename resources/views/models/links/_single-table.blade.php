<tr>
    <td>
        <a href="{{ route('links.show', [$link->id]) }}">
            {{ $link->title }}
        </a>
    </td>
    <td>
        <a href="{{ $link->url }}" target="_blank">
            {{ $link->url }}
            <small><i class="fa fa-external-link"></i></small>
        </a>
    </td>
    <td class="has-text-grey-light">
        <small>{{ $link->created_at->format('Y-m-d H:i') }}</small>
    </td>
    @if(!isset($hide_edit))
        <td>
            <div class="field has-addons">
                <div class="control">
                    <a href="{{ route('links.edit', [$link->id]) }}" class="button is-small">
                        <i class="fa fa-pencil"></i>
                    </a>
                </div>
                <div class="control">
                    <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                        title=" @lang('link.delete')" class="button is-small is-danger">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
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
