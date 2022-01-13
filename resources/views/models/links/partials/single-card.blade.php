<div class="col-12 col-sm-6 col-md-4">
    <div class="h-100 card">
        <div class="card-body h-100 border-bottom-0">
            <div class="d-flex">
                <div class="me-2">
                    {!! $link->getIcon() !!}
                    @if($link->is_private)
                        <br>
                        <x-icon.lock title="@lang('link.private')"/>
                        <span class="visually-hidden">@lang('link.private')</span>
                    @endif
                </div>
                <div>
                    <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->shortTitle() }}</a>
                    <br>
                    <small class="text-muted">{{ $link->shortUrl() }}</small>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <div class="text-muted text-xs me-3 ps-3">
                @lang('linkace.added') {!! $link->addedAt() !!}
            </div>

            <div class="btn-group ms-auto me-2">
                <a href="{{ route('links.show', [$link->id]) }}" class="btn btn-sm btn-link" title="@lang('link.show')">
                    <x-icon.info class="fw"/>
                    <span class="visually-hidden">@lang('link.show')</span>
                </a>

                <a href="{{ route('links.edit', [$link->id]) }}" class="btn btn-sm btn-link" title="@lang('link.edit')">
                    <x-icon.edit class="fw"/>
                    <span class="visually-hidden">@lang('link.edit')</span>
                </a>

                <button type="submit" form="link-delete-{{ $link->id }}" title="@lang('link.delete')"
                    class="btn btn-sm btn-link">
                    <x-icon.trash class="fw"/>
                    <span class="visually-hidden">@lang('link.delete')</span>
                </button>
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
