<div class="card mb-4">

    <div class="card-header">
        <div class="d-flex align-items-top">
            <div class="mr-2">
                @if($link->is_private)
                    <i class="fas fa-lock text-muted mr-1" title="@lang('link.private')"></i>
                @endif
                {!! $link->getIcon('mr-1') !!}
                <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->title }}</a>
                <br>
                <small class="text-muted">({{ $link->shortUrl() }})</small>
            </div>
            <div class="ml-auto text-right">
                <button type="button" class="btn btn-xs btn-outline-primary" title="@lang('sharing.share_link')"
                    data-toggle="collapse" data-target="#sharing-{{ $link->id }}"
                    aria-expanded="false" aria-controls="sharing-{{ $link->id }}">
                    <i class="fas fa-share-alt fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body py-2 px-3">

        <div class="d-flex flex-wrap align-items-center justify-content-end">
            <div class="mr-auto">
                @if($link->tags->count() > 0)
                    <label class="small">@lang('tag.tags'):</label>
                    @foreach($link->tags as $tag)
                        <a href="{{ route('tags.show', [$tag->id]) }}" class="badge badge-light">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                @else
                    <span class="small">@lang('tag.no_tags')</span>
                @endif
            </div>


            <div class="small text-muted mx-2">
                @lang('linkace.added') {!! $link->addedAt() !!}
            </div>

            <div class="btn-group mt-1">
                <a href="{{ route('links.show', [$link->id]) }}" class="btn btn-xs btn-outline-secondary"
                    title="@lang('link.show')">
                    <i class="fas fa-info fa-fw"></i> @lang('link.show')
                </a>

                <a href="{{ route('links.edit', [$link->id]) }}" class="btn btn-xs btn-outline-secondary"
                    title="@lang('link.edit')">
                    <i class="fas fa-edit fa-fw"></i> @lang('link.edit')
                </a>

                <a href="#" title="@lang('link.delete')" class="btn btn-xs btn-outline-secondary"
                    onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();">
                    <i class="fas fa-trash-alt fa-fw"></i> @lang('link.delete')
                </a>
            </div>
        </div>

        <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
            action="{{ route('links.destroy', [$link->id]) }}">
            @method('DELETE')
            @csrf
            <input type="hidden" name="link_id" value="{{ $link->id }}">
        </form>

    </div>

    <div class="collapse" id="sharing-{{ $link->id }}">
        <div class="card-footer">
            <div class="share-links">
                {!! getShareLinks($link) !!}
            </div>
        </div>
    </div>

</div>
