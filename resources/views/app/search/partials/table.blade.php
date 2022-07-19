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
                    @if($link->tags->count() > 0)
                        <div class="mt-1">
                            @foreach($link->tags as $tag)
                                <a href="{{ route('tags.show', ['tag' => $tag]) }}" class="btn btn-xs btn-light">
                                    <x-models.name-with-user :model="$tag"/>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </td>
                <td>
                    <a href="{{ $link->url }}" {!! linkTarget() !!} class="small">
                        {{ $link->shortUrl() }}
                    </a>
                </td>
                <td class="text-muted small">{!! $link->addedAt() !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
