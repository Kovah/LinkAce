<div class="table-responsive">
    <table class="table mb-0">
        <thead>
        <tr>
            <th>@lang('link.title')</th>
            <th>@lang('link.url')</th>
            <th style="min-width:90px;">@lang('linkace.added_at')</th>
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
