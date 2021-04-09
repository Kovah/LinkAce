<div class="card mb-4">

    <div class="card-header">
        <div class="d-flex align-items-top flex-wrap">
            <div class="mr-2 mw-100">
                {!! $link->getIcon('mr-1') !!}
                <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->title }}</a>
                <br>
                <small class="text-muted">{{ $link->shortUrl() }}</small>
            </div>
            <div class="ml-auto text-right">
                <button type="button" class="btn btn-xs btn-outline-secondary" title="@lang('sharing.share_link')"
                    data-toggle="collapse" data-target="#sharing-{{ $link->id }}"
                    aria-expanded="false" aria-controls="sharing-{{ $link->id }}">
                    <x-icon.share class="fw"/>
                </button>
            </div>
        </div>
    </div>

    <div class="collapse" id="sharing-{{ $link->id }}">
        <div class="card-body py-2 px-3">
            <div class="share-links">
                {!! getShareLinks($link) !!}
            </div>
        </div>
    </div>

    <div class="card-body py-2 px-3">

        <div class="row">
            <div class="col-12 col-sm-6 ">

                @if($link->tags->count() > 0)
                    <label class="small mb-0">@lang('tag.tags'):</label>
                    @foreach($link->tags as $tag)
                        @if(!$tag->is_private)
                            <a href="{{ route('guest.tags.show', [$tag->id]) }}"
                                class="badge badge-light">
                                {{ $tag->name }}
                            </a>
                        @endif
                    @endforeach
                @else
                    <span class="small">@lang('tag.no_tags')</span>
                @endif

            </div>
            <div class="col-12 col-sm-6 d-sm-flex align-items-sm-center justify-content-sm-end flex-wrap">
                <div class="small text-muted mt-3 mt-sm-0">
                    @lang('linkace.added') {!! $link->addedAt() !!}
                </div>
            </div>
        </div>

    </div>

</div>
