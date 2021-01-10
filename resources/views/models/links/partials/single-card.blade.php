<div class="col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-body h-100 border-bottom-0">
            <div class="d-flex align-items-top">
                <div class="mr-2">
                    @if($link->is_private)
                        <span>
                            <x-icon.lock class="mr-1" title="@lang('link.private')"/>
                            <span class="sr-only">@lang('link.private')</span>
                        </span>
                    @endif
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

            <div class="btn-group w-100 mt-2 small">
                <a href="{{ route('links.show', [$link->id]) }}" class="card-link" title="@lang('link.show')">
                    <x-icon.info class="fw"/>
                    <span class="sr-only">@lang('link.show')</span>
                </a>

                <a href="{{ route('links.edit', [$link->id]) }}" class="card-link" title="@lang('link.edit')">
                    <x-icon.edit class="fw"/>
                    <span class="sr-only">@lang('link.edit')</span>
                </a>

                <a href="#" title="@lang('link.delete')" class="card-link"
                    onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();">
                    <x-icon.trash class="fw"/>
                    <span class="sr-only">@lang('link.delete')</span>
                </a>
            </div>

            <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                action="{{ route('links.destroy', [$link->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="link_id" value="{{ $link->id }}">
            </form>
        </div>
    </div>

</div>
