@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-lg-row mb-3">
                        @if($link->thumbnail)
                            <a href="{{ $link->url }}" {!! linkTarget() !!}
                            class="rounded d-block mt-lg-1 me-lg-2 align-self-center link-thumbnail link-thumbnail-detail"
                                style="background-image: url('{{ $link->thumbnail }}') ;">
                            </a>
                        @endif
                        <div class="d-sm-inline-block mt-1 mb-2 mb-sm-0">
                            {!! $link->getIcon('me-1 me-sm-2') !!}
                            <x-models.visibility-badge :model="$link" class="me-1 me-sm-2 d-inline-block"/>
                        </div>
                        <h3 class="d-inline-block mb-0">
                            <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->title }}</a>
                        </h3>
                    </div>

                    <div class="text-muted small mt-1 mb-3">
                        <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->url }}</a>
                        <br>
                        @lang('linkace.added_by'): <x-models.author :model="$link"/>
                    </div>

                    @if($link->description)
                        <div>{!! $link->formatted_description !!}</div>
                    @endif

                </div>
            </div>

            @if(getShareLinks($link) !== '')
                <div class="card mt-4">
                    <div class="card-header">
                        @lang('sharing.share_link')
                    </div>
                    <div class="card-body py-2">
                        <div class="share-links">
                            {!! getShareLinks($link) !!}
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="col-12 col-md-4">

            <div class="btn-group w-100 mb-3 mt-4 mt-md-0">
                <a href="{{ route('links.edit', [$link->id]) }}" class="btn btn-sm btn-primary"
                    aria-label="@lang('link.edit')">
                    <x-icon.edit class="me-2"/>
                    <span class="d-none d-sm-inline">@lang('linkace.edit')</span>
                </a>
                <button type="submit" form="link-delete-{{ $link->id }}" aria-label="@lang('link.delete')"
                    class="btn btn-sm btn-outline-danger cursor-pointer">
                    <x-icon.trash class="me-2"/>
                    <span class="d-none d-sm-inline">@lang('linkace.delete')</span>
                </button>
            </div>
            <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                action="{{ route('links.destroy', [$link->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="link_id" value="{{ $link->id }}">
            </form>

            <div class="mb-3">
                <a href="{{ waybackLink($link) }}" class="btn btn-sm w-100 btn-outline-info" target="_blank">
                    @lang('link.wayback')
                </a>
            </div>

            <form action="{{ route('links.toggle-check', [$link->id]) }}" method="POST"
                class="mb-2 d-flex align-items-center">
                @csrf
                @if($link->check_disabled)
                    <small class="me-3">@lang('link.check_disabled')</small>
                    <input type="hidden" name="toggle" value="0">
                    <button type="submit" class="btn btn-xs btn-outline-secondary ms-auto">
                        @lang('link.check_enable')
                    </button>
                @else
                    <small class="me-3">@lang('link.check_enabled')</small>
                    <input type="hidden" name="toggle" value="1">
                    <button type="submit" class="btn btn-xs btn-outline-secondary ms-auto">
                        @lang('link.check_disable')
                    </button>
                @endif
            </form>

            @if($link->status !== 1)
                <form action="{{ route('links.mark-working', [$link->id]) }}" method="POST"
                    class="mt-2 d-flex align-items-center">
                    @csrf
                    <small class="me-3">@lang('link.status_is_broken')</small>
                    <input type="hidden" name="toggle" value="0">
                    <button type="submit" class="btn btn-xs btn-outline-secondary ms-auto">
                        @lang('link.status_mark_working')
                    </button>
                </form>
            @endif

        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    @lang('list.lists')
                </div>
                <div class="card-body py-2">
                    @if(!$link->lists->isEmpty())
                        @foreach($link->lists as $list)
                            <a href="{{ route('lists.show', [$list->id]) }}" class="btn btn-sm btn-light">
                                {{ $list->name }}
                            </a>
                        @endforeach
                    @else
                        <div class="text-muted small">@lang('list.no_lists')</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    @lang('tag.tags')
                </div>
                <div class="card-body py-2">
                    @if(!$link->tags->isEmpty())
                        @foreach($link->tags as $tag)
                            <a href="{{ route('tags.show', [$tag->id]) }}" class="btn btn-sm btn-light">
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

        <h3 class="h6 mb-2">@lang('note.notes')</h3>

        @if($link->notes->count())
            @foreach($link->notes as $note)
                @include('models.notes.partials.single', ['note' =>$note])
            @endforeach
        @endif

        @include('models.notes.partials.create', ['link' => $link])

    </div>

    <div class="link-history mt-5">
        <h3 class="h6 mb-2">@lang('link.history')</h3>

        <div class="history small text-muted">
            @foreach($history as $entry)
                @if($loop->index === 5 && $loop->count >= 10)
                    <a data-bs-toggle="collapse" href="#link-history" role="button" class="d-inline-block mb-1"
                        aria-expanded="false" aria-controls="link-history">
                        @lang('linkace.more')
                        <x-icon.caret-down class="fw"/>
                    </a>
                    <div id="link-history" class="collapse">
                @endif
                <x-history.link-entry :entry="$entry"/>
            @endforeach
            <div>{{ formatDateTime($link->created_at) }}: @lang('link.history_created')</div>
            @if(count($history) >= 10)
                </div>
            @endif
        </div>
    </div>

@endsection
