<div class="card mb-3">

    <div class="card-header">
        <a href="{{ route('links.show', [$link->id]) }}">
            {{ $link->title }}
        </a>
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

        <div class="row text-muted">
            <div class="col d-flex align-items-center">

                <small>
                    {!! $link->addedAt() !!}
                </small>

            </div>
            <div class="col text-right">

                <small>
                    @lang('link.author', ['user' => $link->user->name])
                </small>

            </div>
        </div>

    </div>

</div>
