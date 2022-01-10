<div class="col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-body h-100 border-bottom-0">
            <div class="d-flex">
                <div class="me-2">
                    {!! $link->getIcon('me-2') !!}
                </div>
                <div>
                    <a href="{{ $link->url }}" {!! linkTarget() !!} class="text-decoration-none">
                        {{ $link->shortTitle() }}
                    </a>
                    <br>
                    <small class="text-muted">{{ $link->shortUrl() }}</small>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="text-xs text-muted">
                @lang('linkace.added') {!! $link->addedAt() !!}
            </div>
        </div>
    </div>

</div>
