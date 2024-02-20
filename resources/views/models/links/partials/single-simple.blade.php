@php
    $shareLinks = getShareLinks($link);
@endphp
<li class="link-simple list-group-item">
    <div class="d-sm-flex align-items-center">
        <div class="me-4 one-line-sm">
            {!! $link->getIcon('me-1') !!}
            <a href="{{ $link->url }}" title="{{ $link->url }}" {!! linkTarget() !!}>
                {{ $link->title }}
            </a>
        </div>
        <div class="mt-2 mt-sm-0 ms-auto flex-shrink-0 d-flex align-items-center">
            <small class="text-pale me-2 text-condensed">{!! $link->domainOfURL() !!}</small>
            <a href="{{ route('links.show', [$link]) }}" title="@lang('link.show')" class="me-1">
                <x-icon.info class="fw"/>
                <span class="visually-hidden">@lang('link.details')</span>
            </a>
            <button type="button" class="btn btn-xs btn-link me-1" title="@lang('sharing.share_link')"
                data-bs-toggle="collapse" data-bs-target="#sharing-{{ $link->id }}"
                aria-expanded="false" aria-controls="sharing-{{ $link->id }}">
                <x-icon.share class="fw"/>
                <span class="visually-hidden">@lang('sharing.share_link')</span>
            </button>
            <input type="checkbox" aria-label="@lang('link.bulk_edit_add')" class="bulk-edit-model form-check"
                data-id="{{ $link->id }}">
        </div>
    </div>
    @if($shareLinks !== '')
        <div class="collapse" id="sharing-{{ $link->id }}">
            <div class="share-links justify-content-end mt-1">
                {!! $shareLinks !!}
            </div>
        </div>
    @endif
</li>
