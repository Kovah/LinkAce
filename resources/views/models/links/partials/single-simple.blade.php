<li class="list-group-item">
    <div class="d-sm-flex align-items-center">
        <div class="me-3 one-line-sm">
            <a href="{{ $link->url }}" title="{{ $link->url }}" {!! linkTarget() !!}>
                <x-models.visibility-badge :model="$link" class="d-inline-block"/>
                {!! $link->getIcon('me-1') !!}
                {{ $link->shortTitle(100) }}
            </a>
        </div>
        <div class="mt-2 mt-sm-0 ms-auto flex-shrink-0 d-flex align-items-center">
            <small class="text-muted me-3">{!! $link->domainOfURL() !!}</small>
            @auth()
                <a href="{{ route('links.show', [$link]) }}" title="@lang('link.show')">
                    <x-icon.info class="fw"/>
                    <span class="visually-hidden">@lang('link.details')</span>
                </a>
            @endauth
        </div>
    </div>
</li>
