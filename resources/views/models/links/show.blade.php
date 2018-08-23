@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('link.link')
            </p>
            <a href="{{ route('links.edit', [$link->id]) }}" class="card-header-icon" aria-label="@lang('link.edit')">
                <div class="icon">
                    <i class="fa fa-pencil fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.edit')
            </a>
            <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                class="card-header-icon has-text-danger" aria-label="@lang('link.delete')">
                <div class="icon">
                    <i class="fa fa-trash fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.delete')
            </a>
            <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                action="{{ route('links.destroy', [$link->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="link_id" value="{{ $link->id }}">
            </form>
        </header>
        <div class="card-content">

            <a href="{{ $link->url }}" class="is-size-3">{{ $link->title }}</a>

            <p class="has-text-grey"><a href="{{ $link->url }}">{{ $link->url }}</a></p>

            <br>

            <div class="columns">
                @if($link->description)
                    <div class="column">
                        {{ $link->url }}
                    </div>
                @endif

                <div class="column">
                    @if($link->category)
                        <div class="field">
                            <label>@lang('category.category')</label>
                            <a href="{{ route('categories.show', [$link->category->id]) }}">
                                {{ $link->category->name }}
                            </a>
                        </div>
                    @endif
                    @if(!$link->tags->isEmpty())
                        <div class="field">
                            <label>@lang('tag.tags')</label>
                            @foreach($link->tags as $tag)
                                <a href="{{ route('tags.show', [$tag->id]) }}" class="tag is-primary">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </div>

@endsection
