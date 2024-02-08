@php
    $shareLinks = getShareLinks($link);
@endphp
<div class="link-detailed list-group-item py-3">
    <div class="d-flex w-100">
        <div class="me-2 me-lg-3">
            {!! $link->getIcon() !!}
            <x-models.visibility-badge :model="$link"/>
        </div>
        <div class="min-w-0">
            <a href="{{ $link->url }}" {!! linkTarget() !!} class="two-lines">{{ $link->title }}</a>
            <div class="mt-1 small text-pale w-100 one-line">
                {{ $link->url }}
            </div>
            @if($link->description)
                <div class="small mt-1 two-lines">{{ $link->description }}</div>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 col-sm-6">
            <div class="d-none d-sm-inline-block me-3 me-lg-4">&nbsp;</div>
            @if($link->tags->count() > 0)
                @foreach($link->tags as $tag)
                    <a href="{{ route('tags.show', ['tag' => $tag]) }}" class="btn btn-light btn-xs">
                        {{ $tag->name }}
                    </a>
                @endforeach
            @endif
        </div>
        <div class="col-12 col-sm-6 mt-2 mt-sm-0 d-flex align-items-center justify-content-end flex-wrap">

            <div class="text-xs text-pale">
                @lang('linkace.added') {!! $link->addedAt() !!}
            </div>

            <div class="btn-group ms-2">
                <button type="button" class="btn btn-xs btn-outline-secondary" title="@lang('sharing.share_link')"
                    data-bs-toggle="collapse" data-bs-target="#sharing-{{ $link->id }}"
                    aria-expanded="false" aria-controls="sharing-{{ $link->id }}">
                    <x-icon.share class="fw"/>
                    <span class="visually-hidden">@lang('sharing.share_link')</span>
                </button>
                <a href="{{ route('links.show', [$link]) }}" class="btn btn-xs btn-outline-secondary"
                    title="@lang('link.show')">
                    @lang('linkace.show')
                </a>
                <a href="{{ route('links.edit', [$link]) }}" class="btn btn-xs btn-outline-secondary"
                    title="@lang('link.edit')">
                    @lang('linkace.edit')
                </a>
                <button type="submit" form="link-delete-{{ $link->id }}" title="@lang('link.delete')"
                    class="btn btn-xs btn-outline-secondary">
                    @lang('linkace.delete')
                </button>
            </div>

        </div>
    </div>
    @if($shareLinks !== '')
        <div class="collapse" id="sharing-{{ $link->id }}">
            <div class="share-links justify-content-end mt-1">
                {!! $shareLinks !!}
            </div>
        </div>
    @endif

    <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
        action="{{ route('links.destroy', [$link->id]) }}">
        @method('DELETE')
        @csrf
        <input type="hidden" name="link_id" value="{{ $link->id }}">
    </form>
</div>
