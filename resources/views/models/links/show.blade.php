@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-12 col-md-8">
            <div class="card">
                <header class="card-header">
                    {!! $link->getIcon('mr-2') !!}
                    @lang('link.link')
                    @if($link->is_private)
                        <i class="fa fa-lock" title="@lang('link.private')"></i>
                    @endif
                </header>
                <div class="card-body">

                    <h2>
                        <a href="{{ $link->url }}">{{ $link->title }}</a>
                    </h2>
                    <div class="text-muted small mt-1 mb-3">
                        <a href="{{ $link->url }}">{{ $link->url }}</a>
                    </div>

                    <div class="row">
                        @if($link->description)
                            <div class="col">
                                {{ $link->description }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    @lang('sharing.share_link')
                </div>
                <div class="card-body py-2">
                    <div class="share-links">
                        {!! getShareLinks($link) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-md-4">

            <div class="btn-group btn-block mb-3 mt-3 mt-md-0">
                <a href="{{ route('links.edit', [$link->id]) }}" class="btn btn-sm btn-primary"
                    aria-label="@lang('link.edit')">
                    <i class="fa fa-edit fa-mr" aria-hidden="true"></i>
                    <span class="d-none d-sm-inline">@lang('linkace.edit')</span>
                </a>
                <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                    class="btn btn-sm btn-outline-danger cursor-pointer" aria-label="@lang('link.delete')">
                    <i class="fa fa-trash fa-mr" aria-hidden="true"></i>
                    <span class="d-none d-sm-inline">@lang('linkace.delete')</span>
                </a>
            </div>
            <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                action="{{ route('links.destroy', [$link->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="link_id" value="{{ $link->id }}">
            </form>

            @if($link->status !== 1)
                <div class="mb-3">
                    <a href="{{ waybackLink($link) }}" class="btn btn-sm btn-block btn-outline-warning" target="_blank">
                        @lang('link.wayback')
                    </a>
                </div>
            @endif

            <div class="card mb-3">
                <div class="card-header">
                    @lang('category.category')
                </div>
                <div class="card-body py-2">
                    @if($link->category)
                        @if($link->category->parentCategory)
                            <a href="{{ route('categories.show', [$link->category->parentCategory->id]) }}">
                                {{ $link->category->parentCategory->name }}
                            </a>&nbsp;&leftarrow;&nbsp;
                        @endif
                        <a href="{{ route('categories.show', [$link->category->id]) }}">
                            {{ $link->category->name }}
                        </a>
                    @else
                        <div class="text-muted small">@lang('category.no_category')</div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    @lang('tag.tags')
                </div>
                <div class="card-body py-2">
                    @if(!$link->tags->isEmpty())
                        @foreach($link->tags as $tag)
                            <a href="{{ route('tags.show', [$tag->id]) }}" class="badge badge-primary badge-pill">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    @else
                        <div class="text-muted small">@lang('tag.no_tags')</div>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <div class="link-notes mt-4">

        <h3 class="h4 mb-2">@lang('note.notes')</h3>

        @if($link->notes->count())
            @foreach($link->notes as $note)
                @include('models.notes.partials.single', ['note' =>$note])
            @endforeach
        @endif

        @include('models.notes.partials.create', ['link' => $link])

    </div>

@endsection
