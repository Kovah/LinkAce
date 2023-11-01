<div class="card mb-4">

    <div class="card-body">
        <div class="d-flex align-items-top flex-wrap">
            <div class="me-2 mw-100 d-flex">
                <div class="me-2">
                    {!! $link->getIcon() !!}
                </div>
                <div>
                    <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->title }}</a>
                    <br>
                    <small class="text-pale">{{ $link->shortUrl() }}</small>
                </div>
            </div>
            <div class="ms-auto text-end">
                <button type="button" class="btn btn-xs btn-outline-secondary" title="@lang('sharing.share_link')"
                    data-bs-toggle="collapse" data-bs-target="#sharing-{{ $link->id }}"
                    aria-expanded="false" aria-controls="sharing-{{ $link->id }}">
                    <x-icon.share class="fw"/>
                </button>
            </div>
        </div>
    </div>

    <div class="collapse" id="sharing-{{ $link->id }}">
        <div class="card-body py-0">
            <div class="share-links">
                {!! getShareLinks($link) !!}
            </div>
        </div>
    </div>

    <div class="card-body py-2">

        <div class="row">
            <div class="col-12 col-sm-6">

                @if($link->tags->count() > 0)
                    @foreach($link->tags as $tag)
                        @if(!$tag->is_private)
                            <a href="{{ route('guest.tags.show', [$tag->id]) }}"
                                class="btn btn-xs btn-light">
                                {{ $tag->name }}
                            </a>
                        @endif
                    @endforeach
                @else
                    <span class="small text-pale">@lang('tag.no_tags')</span>
                @endif

            </div>
            <div class="col-12 col-sm-6 d-sm-flex align-items-sm-center justify-content-sm-end flex-wrap">
                <div class="text-xs text-pale mt-3 mt-sm-0">
                    @lang('linkace.added') {!! $link->addedAt() !!}
                </div>
            </div>
        </div>

    </div>

</div>
