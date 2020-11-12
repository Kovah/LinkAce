@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">
            <span class="mr-3">
                @lang('tag.tag')
            </span>
            <div class="ml-auto">
                <a href="{{ route('tags.edit', [$tag->id]) }}" class="btn btn-sm btn-primary"
                    aria-label="@lang('tag.edit')">
                    <x-icon.edit class="mr-2"/>
                    @lang('linkace.edit')
                </a>
                <a onclick="event.preventDefault();document.getElementById('tag-delete-{{ $tag->id }}').submit();"
                    class="btn btn-sm btn-outline-danger" aria-label="@lang('tag.delete')">
                    <x-icon.trash class="mr-2"/>
                    @lang('linkace.delete')
                </a>
            </div>
            <form id="tag-delete-{{ $tag->id }}" method="POST" style="display: none;"
                action="{{ route('tags.destroy', [$tag->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="tag_id" value="{{ $tag->id }}">
            </form>
        </header>
        <div class="card-body">

            <h2 class="mb-0">{{ $tag->name }}</h2>

        </div>
    </div>

    <div class="card my-3">
        <div class="card-header">
            @lang('link.links')
        </div>
        <div class="card-table">

            @include('models.links.partials.table', ['links' => $tagLinks])

        </div>
    </div>

    {!! $tagLinks->onEachSide(1)->appends(['orderBy' => $orderBy, 'orderDir' => $orderDir])->links() !!}

@endsection
