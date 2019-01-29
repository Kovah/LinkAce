@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">
            <span class="mr-3">
                @lang('link.link')
                @if($link->is_private)
                    <i class="fa fa-lock" title="@lang('link.private')"></i>
                @endif
            </span>
            <div class="ml-auto">
                <a href="{{ route('links.edit', [$link->id]) }}" class="btn btn-sm btn-primary"
                    aria-label="@lang('link.edit')">
                    <i class="fa fa-edit fa-mr" aria-hidden="true"></i>
                    @lang('linkace.edit')
                </a>
                <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                    class="btn btn-sm btn-outline-danger" aria-label="@lang('link.delete')">
                    <i class="fa fa-trash fa-mr" aria-hidden="true"></i>
                    @lang('linkace.delete')
                </a>
            </div>
            <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                action="{{ route('links.destroy', [$link->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="link_id" value="{{ $link->id }}">
            </form>
        </header>
        <div class="card-body">

            <h2>
                <a href="{{ $link->url }}">{{ $link->title }}</a>
            </h2>

            <div class="text-muted">
                <a href="{{ $link->url }}">{{ $link->url }}</a>
            </div>

            <div class="row mt-3">
                @if($link->description)
                    <div class="col">
                        {{ $link->description }}
                    </div>
                @endif

                <div class="col">

                    <div class="row">
                        <div class="col">
                            @if($link->category)
                                <label>@lang('category.category'):</label>

                                @if($link->category->parentCategory)
                                    <a href="{{ route('categories.show', [$link->category->parentCategory->id]) }}">
                                        {{ $link->category->parentCategory->name }}
                                    </a>&nbsp;&leftarrow;&nbsp;
                                @endif

                                <a href="{{ route('categories.show', [$link->category->id]) }}">
                                    {{ $link->category->name }}
                                </a>
                            @endif
                        </div>
                        <div class="col">
                            @if(!$link->tags->isEmpty())
                                <label>@lang('tag.tags')</label>
                                @foreach($link->tags as $tag)
                                    <a href="{{ route('tags.show', [$tag->id]) }}" class="badge badge-light">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>
            </div>


        </div>
        <div class="card-body">

            <label>@lang('sharing.share_link')</label>

            <div class="share-links">
                {!! getShareLinks($link) !!}
            </div>

        </div>
    </div>

    <div class="link-notes mt-4">

        <h3>@lang('note.notes')</h3>

        @if($link->notes->count())
            @foreach($link->notes as $note)
                @include('models.notes.partials.single', ['note' =>$note])
            @endforeach
        @endif

        @include('models.notes.partials.create', ['link' => $link])

    </div>

@endsection
