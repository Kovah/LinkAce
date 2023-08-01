<li class="list-group-item">
    <div class="d-sm-flex align-items-center">
        <div class="me-3 one-line-sm">
            <a href="{{ $link->url }}" title="{{ $link->url }}">
                {!! $link->getIcon('me-2') !!}
                {{ $link->shortTitle(100) }}
            </a>
        </div>
        <div class="mt-2 mt-sm-0 ms-auto flex-shrink-0">
            <small class="text-pale">{!! $link->domainOfURL() !!}</small>
        </div>
    </div>
</li>
