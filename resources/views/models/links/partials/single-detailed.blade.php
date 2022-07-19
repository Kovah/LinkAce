<div class="card mb-4">

    <div class="card-body">
        <div class="d-flex align-items-top flex-wrap flex-md-nowrap">

            <div class="link-thumbnail-list-holder order-0 me-2 me-lg-3 mb-2 mb-md-0">
                <a href="{{ $link->url }}" {!! linkTarget() !!} class="link-thumbnail link-thumbnail-list"
                    @if($link->thumbnail)
                    style="background-image: url('{{ $link->thumbnail }}');"
                    @endif>
                    @if(!$link->thumbnail)
                        <span class="link-thumbnail-placeholder"><x-icon.linkace-icon/></span>
                    @endif
                </a>
            </div>

            <div class="d-flex me-2 mw-100 order-2 order-md-1">
                <div class="me-2 me-lg-3">
                    {!! $link->getIcon() !!}
                    <x-models.visibility-badge :model="$link"/>
                </div>
                <div>
                    <div><a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->title }}</a></div>
                    <div><small class="text-muted">{{ $link->shortUrl(75) }}</small></div>
                    @if($link->description)
                        <div class="text-pale"><small>{{ Str::limit($link->description, 200) }}</small></div>
                    @endif
                </div>
            </div>
            @if(getShareLinks($link) !== '')
                <div class="ms-auto text-end order-1 order-md-2">
                    <button type="button" class="btn btn-xs btn-md-sm btn-outline-secondary" title="@lang('sharing.share_link')"
                        data-bs-toggle="collapse" data-bs-target="#sharing-{{ $link->id }}"
                        aria-expanded="false" aria-controls="sharing-{{ $link->id }}">
                        <x-icon.share class="fw"/>
                        <span class="visually-hidden">@lang('sharing.share_link')</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if(getShareLinks($link) !== '')
        <div class="collapse" id="sharing-{{ $link->id }}">
            <div class="card-body py-0">
                <div class="share-links">
                    {!! getShareLinks($link) !!}
                </div>
            </div>
        </div>
    @endif

    <div class="card-body py-2">

        <div class="row">
            <div class="col-12 col-sm-6">

                @if($link->tags->count() > 0)
                    @foreach($link->tags as $tag)
                        <a href="{{ route('tags.show', ['tag' => $tag]) }}" class="btn btn-light btn-xs">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                @else
                    <span class="small text-muted">@lang('tag.no_tags')</span>
                @endif

            </div>
            <div class="col-12 col-sm-6 d-sm-flex align-items-sm-center justify-content-sm-end flex-wrap">

                <div class="text-xs text-muted mt-3 mt-sm-0">
                    @lang('linkace.added') {!! $link->addedAt() !!}
                </div>

                <div class="btn-group mt-1 ms-md-2">
                    <a href="{{ route('links.show', [$link]) }}" class="btn btn-xs btn-light"
                        title="@lang('link.show')">
                        <x-icon.info class="fw"/> @lang('link.show')
                    </a>

                    <a href="{{ route('links.edit', [$link]) }}" class="btn btn-xs btn-light"
                        title="@lang('link.edit')">
                        <x-icon.edit class="fw"/> @lang('link.edit')
                    </a>

                    <button type="submit" form="link-delete-{{ $link->id }}" title="@lang('link.delete')"
                        class="btn btn-xs btn-light">
                        <x-icon.trash class="fw"/> @lang('link.delete')
                    </button>
                </div>

            </div>
        </div>

        <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
            action="{{ route('links.destroy', [$link->id]) }}">
            @method('DELETE')
            @csrf
            <input type="hidden" name="link_id" value="{{ $link->id }}">
        </form>

    </div>

</div>
