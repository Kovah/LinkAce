@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">
            <span class="me-3">
                <x-models.visibility-badge :model="$tag" class="d-inline-block me-1"/>
                @lang('tag.tag')
            </span>
            <div class="ms-auto">
                <a href="{{ route('tags.edit', ['tag' => $tag]) }}" class="btn btn-sm btn-primary"
                    aria-label="@lang('tag.edit')">
                    <x-icon.edit class="me-2"/>
                    @lang('linkace.edit')
                </a>
                <a onclick="event.preventDefault();document.getElementById('tag-delete-{{ $tag->id }}').submit();"
                    class="btn btn-sm btn-outline-danger" aria-label="@lang('tag.delete')">
                    <x-icon.trash class="me-2"/>
                    @lang('linkace.delete')
                </a>
            </div>
            <form id="tag-delete-{{ $tag->id }}" method="POST" style="display: none;"
                action="{{ route('tags.destroy', ['tag' => $tag]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="tag_id" value="{{ $tag->id }}">
            </form>
        </header>
        <div class="card-body">

            <h2 class="mb-0">{{ $tag->name }}</h2>

            <div class="mt-2 small">
                @lang('linkace.added_by'): <x-models.author :model="$tag"/>
            </div>

        </div>
    </div>

    <section class="my-4">
        @if($links->isNotEmpty())
            <div class="d-flex align-items-center mb-4">
                <x-models.link-display-toggles class="ms-auto"/>
                <x-models.link-order-dropdown class="ms-3"/>
            </div>
            <div class="link-wrapper">
                @if(usersettings('link_display_mode') === Link::DISPLAY_CARDS)
                    @include('models.links.partials.list-cards')
                @elseif(usersettings('link_display_mode') === Link::DISPLAY_LIST_SIMPLE)
                    @include('models.links.partials.list-simple')
                @else
                    @include('models.links.partials.list-detailed')
                @endif
            </div>

        @else

            <div class="alert alert-info">
                @lang('linkace.no_results_found', ['model' => trans('link.links')])
            </div>

        @endif
    </section>

    @if($links->isNotEmpty())
        {!! $links->onEachSide(1)->withQueryString()->links() !!}
    @endif

    <div class="list-history mt-5">
        <h3 class="h6 mb-2">@lang('linkace.history')</h3>

        <div class="history small text-muted">
            @foreach($history as $entry)
                @if($loop->index === 5 && $loop->count >= 10)
                    <a data-bs-toggle="collapse" href="#tag-history" role="button" class="d-inline-block mb-1"
                        aria-expanded="false" aria-controls="tag-history">
                        @lang('linkace.more')
                        <x-icon.caret-down class="fw"/>
                    </a>
                    <div id="tag-history" class="collapse">
                @endif
                <x-history.tag-entry :entry="$entry"/>
            @endforeach
            <div>{{ formatDateTime($tag->created_at) }}: @lang('tag.history_created')</div>
            @if(count($history) >= 10)
                </div>
            @endif
        </div>
    </div>

@endsection
