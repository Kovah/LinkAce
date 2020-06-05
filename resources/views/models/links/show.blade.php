@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-12 col-md-8">
            <div class="card">
                <header class="card-header">
                    {!! $link->getIcon('mr-2') !!}
                    @lang('link.link')
                    @if($link->is_private)
                        <i class="fas fa-lock" title="@lang('link.private')"></i>
                    @endif
                </header>
                <div class="card-body">

                    <h2>
                        <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->title }}</a>
                    </h2>
                    <div class="text-muted small mt-1 mb-3">
                        <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->url }}</a>
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
                    <i class="fas fa-edit mr-2"></i>
                    <span class="d-none d-sm-inline">@lang('linkace.edit')</span>
                </a>
                <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                    class="btn btn-sm btn-outline-danger cursor-pointer" aria-label="@lang('link.delete')">
                    <i class="fas fa-trash-alt mr-2"></i>
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
                <div class="mb-2">
                    <a href="{{ waybackLink($link) }}" class="btn btn-sm btn-block btn-outline-warning" target="_blank">
                        @lang('link.wayback')
                    </a>
                </div>

                <form action="{{ route('links.toggle-check', [$link->id]) }}" method="POST"
                    class="mb-3 d-flex align-items-center">
                    @csrf
                    @if($link->check_disabled)
                        <small class="mr-3">@lang('link.check_disabled')</small>
                        <input type="hidden" name="toggle" value="0">
                        <button type="submit" class="btn btn-xs btn-outline-secondary ml-auto">
                            @lang('link.check_enable')
                        </button>
                    @else
                        <small class="mr-3">@lang('link.check_enabled')</small>
                        <input type="hidden" name="toggle" value="1">
                        <button type="submit" class="btn btn-xs btn-outline-secondary ml-auto">
                            @lang('link.check_disable')
                        </button>
                    @endif
                </form>
            @endif

            <div class="card mb-3">
                <div class="card-header">
                    @lang('list.lists')
                </div>
                <div class="card-body py-2">
                    @if(!$link->lists->isEmpty())
                        @foreach($link->lists as $list)
                            <a href="{{ route('lists.show', [$list->id]) }}" class="badge badge-primary badge-pill">
                                {{ $list->name }}
                            </a>
                        @endforeach
                    @else
                        <div class="text-muted small">@lang('list.no_lists')</div>
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

    <div class="link-notes mt-5">

        <h3 class="h4 mb-2">@lang('note.notes')</h3>

        @if($link->notes->count())
            @foreach($link->notes as $note)
                @include('models.notes.partials.single', ['note' =>$note])
            @endforeach
        @endif

        @include('models.notes.partials.create', ['link' => $link])

    </div>

    <div class="link-history mt-5">
        <h3 class="h6 mb-2">@lang('link.history')</h3>

        <div class="small text-muted">
            @foreach($link->revisionHistory()->latest()->get() as $entry)
                <x-links.history-entry :entry="$entry"/>
            @endforeach
        </div>
    </div>

@endsection
