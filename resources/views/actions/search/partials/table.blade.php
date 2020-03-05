<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
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
                    <a href="{{ $link->url }}" {!! linkTarget() !!}>
                        {{ $link->url }}
                    </a>
                </td>
                <td class="text-muted">
                    <small>{!! $link->addedAt() !!}</small>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
