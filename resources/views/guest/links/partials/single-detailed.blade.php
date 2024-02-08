@php
    $shareLinks = getShareLinks($link);
@endphp
<div class="link-detailed list-group-item py-3">
    <div class="d-flex w-100">
        <div class="me-2 me-lg-3">
            {!! $link->getIcon() !!}
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
                    @if(!$tag->is_private)
                        <a href="{{ route('guest.tags.show', ['tag' => $tag]) }}"
                            class="btn btn-xs btn-light">
                            {{ $tag->name }}
                        </a>
                    @endif
                @endforeach
            @endif

        </div>
        <div class="col-12 col-sm-6 mt-2 mt-sm-0 d-flex align-items-center justify-content-end flex-wrap">
            <div class="text-xs text-pale mt-3 mt-sm-0 me-3">
                @lang('linkace.added') {!! $link->addedAt() !!}
            </div>
            <button type="button" class="btn btn-xs btn-md-sm btn-link" title="@lang('sharing.share_link')"
                data-bs-toggle="collapse" data-bs-target="#sharing-{{ $link->id }}"
                aria-expanded="false" aria-controls="sharing-{{ $link->id }}">
                <x-icon.share class="fw"/>
                <span class="visually-hidden">@lang('sharing.share_link')</span>
            </button>
        </div>
    </div>
    @if($shareLinks !== '')
        <div class="collapse" id="sharing-{{ $link->id }}">
            <div class="share-links justify-content-end mt-1">
                {!! $shareLinks !!}
            </div>
        </div>
    @endif
</div>
