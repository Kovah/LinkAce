<div class="col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-body h-100 border-bottom-0">
            <div class="d-flex">
                <div class="me-2">
                    {!! $link->getIcon() !!}
                </div>
                <div>
                    <a href="{{ $link->url }}" {!! linkTarget() !!}>
                        {{ $link->shortTitle() }}
                    </a>
                    <br>
                    <small class="text-muted">{{ $link->shortUrl() }}</small>
                </div>
            </div>
        </div>

        <div class="px-3 py-2 text-xs text-muted text-end">
            @lang('linkace.added') {!! $link->addedAt() !!}
        </div>
    </div>

</div>
