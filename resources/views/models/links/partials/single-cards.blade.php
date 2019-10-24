<div class="col-12 col-sm-6 col-md-4 mb-4">
    <div class="h-100 card">
        <div class="card-header">
            <div class="d-flex align-items-top">
                <div class="mr-2">
                    @if($link->is_private)
                        <i class="fas fa-lock text-muted mr-1" title="@lang('link.private')"></i>
                    @endif
                    {!! $link->getIcon('mr-1') !!}
                    <a href="{{ $link->url }}">{{ $link->title }}</a>
                    <br>
                    <small class="text-muted">({{ $link->shortUrl() }})</small>
                </div>
            </div>
        </div>

        <ul class="list-group list-group-flush h-100">
            <li class="list-group-item">
                @if($link->category)
                    <label class="small m-0">@lang('category.category'):</label>
                    <a href="{{ route('categories.show', [$link->category->id]) }}" class="small">
                        {{ $link->category->name }}
                    </a>
                @else
                    <label class="small">@lang('category.no_category')</label>
                @endif
            </li>
            <li class="list-group-item h-100">
                @if($link->tags->count() > 0)
                    <label class="small m-0">@lang('tag.tags'):</label>
                    @foreach($link->tags as $tag)
                        <a href="{{ route('tags.show', [$tag->id]) }}" class="badge badge-light">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                @else
                    <span class="small">@lang('tag.no_tags')</span>
                @endif
            </li>
        </ul>
        <div class="card-footer">
            <div>
                <small class="text-muted">
                    @lang('linkace.added') {!! $link->addedAt() !!}
                </small>
            </div>

            <div class="btn-group w-100 mt-2">
                <a href="{{ route('links.show', [$link->id]) }}" class="card-link" title="@lang('link.show')">
                    <i class="fas fa-info fa-fw"></i>
                </a>

                <a href="{{ route('links.edit', [$link->id]) }}" class="card-link" title="@lang('link.edit')">
                    <i class="fas fa-edit fa-fw"></i>
                </a>

                <a href="#" title="@lang('link.delete')" class="card-link"
                    onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();">
                    <i class="fas fa-trash-alt fa-fw"></i>
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
