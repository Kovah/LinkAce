<div class="col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-body h-100 border-bottom-0">
            <div class="d-flex align-items-top">
                <div class="mr-2">
                    {!! $link->getIcon('mr-1') !!}
                    <a href="{{ $link->url }}" {!! linkTarget() !!}>
                        {{ $link->shortTitle() }}
                    </a>
                    <div class="mt-2 small text-muted">{{ $link->shortUrl() }}</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div>
                <small class="text-muted">
                    @lang('linkace.added') {!! $link->addedAt() !!}
                </small>
            </div>
        </div>
    </div>

</div>
