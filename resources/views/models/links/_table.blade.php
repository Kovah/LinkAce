<table class="table">
    <thead>
    <tr>
        <th>@lang('link.title')</th>
        <th>@lang('link.url')</th>
        <th>@lang('linkace.added_at')</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($links as $link)
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
            <td>
                <a href="{{ route('links.edit', [$link->id]) }}" class="button is-small">
                    <i class="fa fa-pencil"></i>
                </a>
                <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                    title=" @lang('link.delete')" class="button is-small is-danger">
                    <i class="fa fa-trash"></i>
                </a>
                <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                    action="{{ route('links.destroy', [$link->id]) }}">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="link_id" value="{{ $link->id }}">
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
