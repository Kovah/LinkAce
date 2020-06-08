<li class="list-group-item">
    <div class="d-sm-flex align-items-center">
        <div class="mr-3 one-line-sm">
            <a href="{{ $link->url }}" title="{{ $link->url }}">
                @if($link->is_private)
                    <i class="fa fa-lock mr-1" title="@lang('link.private')"></i>
                @endif
                {!! $link->getIcon('mr-1') !!}
                {{ $link->shortTitle(100) }}
            </a>
        </div>
        <div class="mt-2 mt-sm-0 ml-auto flex-shrink-0 d-flex align-items-center">
            <small class="text-muted mr-3">{!! $link->domainOfURL() !!}</small>
            <a href="{{ route('links.show', [$link->id]) }}" title="@lang('link.show')">
                <i class="fas fa-eye fa-fw"></i>
            </a>
        </div>
    </div>
</li>
