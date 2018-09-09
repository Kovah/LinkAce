@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('tag.tag')
            </p>
            <a href="{{ route('tags.edit', [$tag->id]) }}" class="card-header-icon" aria-label="@lang('tag.edit')">
                <div class="icon">
                    <i class="fa fa-pencil fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.edit')
            </a>
            <a onclick="event.preventDefault();document.getElementById('tag-delete-{{ $tag->id }}').submit();"
                class="card-header-icon has-text-danger" aria-label="@lang('tag.delete')">
                <div class="icon">
                    <i class="fa fa-trash fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.delete')
            </a>
            <form id="tag-delete-{{ $tag->id }}" method="POST" style="display: none;"
                action="{{ route('tags.destroy', [$tag->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="tag_id" value="{{ $tag->id }}">
            </form>
        </header>
        <div class="card-content">

            <h2 class="is-size-3">{{ $tag->name }}</h2>

        </div>
    </div>

    <br>

    <div class="card">
        <div class="card-header">
            <p class="card-header-title">
                @lang('link.links')
            </p>
        </div>
        <div class="card-content">
            @include('models.links._table', ['links' => $tag_links])
        </div>
        @include('partials.card-pagination', ['paginator' => $tag_links])
    </div>

@endsection
