<div class="card mb-4">

    <div class="card-header">
        <div class="d-flex align-items-top flex-wrap flex-md-nowrap">
            @if($link->thumbnail)
                <div class="d-flex justify-content-center mr-2 mb-2 mb-md-0 link-thumbnail-list-holder">
                    <a href="{{ $link->url }}" {!! linkTarget() !!} class="rounded d-block link-thumbnail link-thumbnail-list"
                        style="background-image: url('{{ $link->thumbnail }}');">
                    </a>
                </div>
            @endif
            <div class="mr-2 mw-100">
                @if($link->is_private)
                    <span>
                        <x-icon.lock class="mr-1" title="@lang('link.private')"/>
                        <span class="sr-only">@lang('link.private')</span>
                    </span>
                @endif
                {!! $link->getIcon('mr-1') !!}
                <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->title }}</a>
                <br>
                <small class="text-muted">{{ $link->shortUrl() }}</small>
            </div>
            @if(getShareLinks($link) !== '')
                <div class="ml-auto text-right">
                    <button type="button" class="btn btn-xs btn-outline-secondary" title="@lang('sharing.share_link')"
                        data-toggle="collapse" data-target="#sharing-{{ $link->id }}"
                        aria-expanded="false" aria-controls="sharing-{{ $link->id }}">
                        <x-icon.share class="fw"/>
                        <span class="sr-only">@lang('sharing.share_link')</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if(getShareLinks($link) !== '')
        <div class="collapse" id="sharing-{{ $link->id }}">
            <div class="card-body py-2 px-3">
                <div class="share-links">
                    {!! getShareLinks($link) !!}
                </div>
            </div>
        </div>
    @endif

    <div class="card-body py-2 px-3">

        <div class="row">
            <div class="col-12 col-sm-6 ">

                @if($link->tags->count() > 0)
                    <label class="small mb-0">@lang('tag.tags'):</label>
                    @foreach($link->tags as $tag)
                        <a href="{{ route('tags.show', [$tag->id]) }}" class="badge badge-light">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                @else
                    <span class="small">@lang('tag.no_tags')</span>
                @endif

            </div>
            <div class="col-12 col-sm-6 d-sm-flex align-items-sm-center justify-content-sm-end flex-wrap">

                <div class="small text-muted mt-3 mt-sm-0">
                    @lang('linkace.added') {!! $link->addedAt() !!}
                </div>

                <div class="btn-group mt-1 ml-md-2">
                    <a href="{{ route('links.show', [$link->id]) }}" class="btn btn-xs btn-outline-secondary"
                        title="@lang('link.show')">
                        <x-icon.info class="fw"/> @lang('link.show')
                    </a>

                    <a href="{{ route('links.edit', [$link->id]) }}" class="btn btn-xs btn-outline-secondary"
                        title="@lang('link.edit')">
                        <x-icon.edit class="fw"/> @lang('link.edit')
                    </a>

                    <a href="#" title="@lang('link.delete')" class="btn btn-xs btn-outline-secondary"
                        onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();">
                        <x-icon.trash class="fw"/> @lang('link.delete')
                    </a>
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
