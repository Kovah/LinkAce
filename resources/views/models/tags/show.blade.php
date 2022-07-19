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

    <div class="card my-3">
        <div class="card-table">
            @include('models.links.partials.table', ['links' => $tagLinks])
        </div>
    </div>

    {!! $tagLinks->onEachSide(1)->withQueryString()->links() !!}

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
