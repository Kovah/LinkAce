<div class="search-table table-responsive">
    <table class="table mb-0">
        <thead>
        <tr>
            <th>@lang('link.title')</th>
            <th>@lang('link.url')</th>
            <th style="min-width:90px;">@lang('linkace.added_at')</th>
        </tr>
        </thead>
        <tbody class="link-listing">
        @foreach($results as $link)
            <tr>
                <td>
                    <a href="{{ $link->url }}" {!! linkTarget() !!} class="title">
                        {{ $link->title }}
                    </a>
                    @if($link->tags->count() > 0)
                        <div class="mt-1">
                            @foreach($link->tags as $tag)
                                <a href="{{ route('guest.tags.show', ['tag' => $tag]) }}"
                                    class="btn btn-xs btn-light">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </td>
                <td class="meta text-condensed">
                    <a href="{{ $link->url }}" {!! linkTarget() !!} class="small short-text">
                        {{ $link->shortUrl() }}
                    </a>
                </td>
                <td class="meta text-pale small text-condensed">{!! $link->addedAt() !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $results->onEachSide(1)->withQueryString()->links() !!}
</div>
