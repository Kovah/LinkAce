@php
    $shareLinks = getShareLinks($link);
@endphp
<div class="link-card col-12 col-md-6 col-lg-4">
    <div class="h-100 card">

        <div class="link-thumbnail-list-holder-detailed">
            <a href="{{ $link->url }}" {!! linkTarget() !!} class="link-thumbnail-list-detailed"
                @if($link->thumbnail)
                    style="background-image: url('{{ $link->thumbnail }}');"
                @endif>
                @if(!$link->thumbnail)
                    <span class="link-thumbnail-placeholder link-thumbnail-placeholder-detailed">
                        <x-icon.linkace-icon/>
                    </span>
                @endif
            </a>
        </div>

        <div class="card-body h-100 border-bottom-0">
            <a href="{{ $link->url }}" {!! linkTarget() !!} class="two-lines">{{ $link->title }}</a>
            <div class="mt-1 small text-pale w-100 one-line">
                {{ $link->url }}
            </div>
        </div>

        @if($link->tags->count() > 0)
            <div class="px-3">
                @foreach($link->tags as $tag)
                    <a href="{{ route('tags.show', [$tag]) }}" class="btn btn-light btn-xs">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="d-flex align-items-center my-1">
            <div class="text-pale text-xs me-3 ps-3">
                @lang('linkace.added') {!! $link->addedAt() !!}
            </div>

            <div class="btn-group ms-auto me-2">
                <button type="button" class="btn btn-xs btn-md-sm btn-outline-secondary"
                    title="@lang('sharing.share_link')"
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

            <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                action="{{ route('links.destroy', [$link->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="link_id" value="{{ $link->id }}">
            </form>
        </div>
        @if($shareLinks !== '')
            <div class="collapse" id="sharing-{{ $link->id }}">
                <div class="share-links my-1 mx-3">
                    {!! $shareLinks !!}
                </div>
            </div>
        @endif
    </div>
</div>
