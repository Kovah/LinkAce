<div class="col-12 col-md-6 col-lg-4">
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
            <div class="d-flex">
                <div class="me-2">
                    {!! $link->getIcon() !!}
                    <x-models.visibility-badge :model="$link"/>
                </div>
                <div>
                    <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->shortTitle() }}</a>
                    <br>
                    <small class="text-pale">{{ $link->shortUrl() }}</small>
                </div>
            </div>
        </div>

        <div class="px-3">
            @if($link->tags->count() > 0)
                @foreach($link->tags as $tag)
                    <a href="{{ route('tags.show', ['tag' => $tag]) }}" class="btn btn-light btn-xs">
                        {{ $tag->name }}
                    </a>
                @endforeach
            @else
                <span class="small text-pale">@lang('tag.no_tags')</span>
            @endif
        </div>

        <div class="d-flex align-items-center my-1">
            <div class="text-pale text-xs me-3 ps-3">
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
