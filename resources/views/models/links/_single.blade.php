<div class="card mb-3">

    <div class="card-header clearfix">
        <a href="{{ route('links.show', [$link->id]) }}">
            {{ $link->title }}
        </a>
        @if($link->is_private)
            <i class="fa fa-lock text-muted" title="@lang('link.private')"></i>
        @endif
    </div>

    <div class="card-body py-2 px-3">

        <small>
            <a href="{{ $link->url }}" target="_blank" title="@lang('link.external_link')">
                {{ $link->url }}
            </a>
        </small>

        <div class="row mt-3">
            <div class="col col-md-4 small">

                @if($link->category)
                    <label>@lang('category.category'):</label>
                    <a href="{{ route('categories.show', [$link->category->id]) }}">
                        {{ $link->category->name }}
                    </a>
                @else
                    @lang('category.no_category')
                @endif

            </div>
            <div class="col col-md-8 small">

                @if($link->tags->count() > 0)
                    <label>@lang('tag.tags'):</label>
                    @foreach($link->tags as $tag)
                        <a href="{{ route('tags.show', [$tag->id]) }}" class="badge badge-light">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                @else
                    @lang('tag.no_tags')
                @endif

            </div>
        </div>

        <div class="row">
            <div class="col d-flex align-items-center">

                <small class="text-muted">
                    {!! $link->addedAt() !!}
                </small>

            </div>
            <div class="col text-right">

                <a href="{{ route('links.edit', [$link->id]) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-pencil"></i>
                </a>
                <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                    title=" @lang('link.delete')" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-trash"></i>
                </a>

                <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                    action="{{ route('links.destroy', [$link->id]) }}">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="link_id" value="{{ $link->id }}">
                </form>

            </div>
        </div>

    </div>

</div>
