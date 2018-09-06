<table class="table">
    <thead>
    <tr>
        <th>@lang('link.title')</th>
        <th>@lang('link.url')</th>
        <th>@lang('linkace.added_at')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $link)
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
        </tr>
    @endforeach
    </tbody>
</table>
