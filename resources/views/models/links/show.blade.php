@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-lg-row mb-3">
                        @if($link->thumbnail)
                            <a href="{{ $link->url }}" {!! linkTarget() !!}
                            class="rounded d-block mt-lg-1 mr-lg-2 align-self-center link-thumbnail link-thumbnail-detail"
                                style="background-image: url('{{ $link->thumbnail }}') ;">
                            </a>
                        @endif
                        <div class="d-sm-inline-block mt-1 mb-2 mb-sm-0">
                            {!! $link->getIcon('mr-1 mr-sm-2') !!}
                            @if($link->is_private)
                                <x-icon.lock class="fw mr-1 mr-sm-2" title="@lang('link.private')"/>
                            @endif
                        </div>
                        <h3 class="d-inline-block mb-0">
                            <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->title }}</a>
                        </h3>
                    </div>

                    <div class="text-muted small mt-1 mb-3">
                        <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->url }}</a>
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

            <div class="btn-group btn-block mb-3 mt-4 mt-md-0">
                <a href="{{ route('links.edit', [$link->id]) }}" class="btn btn-sm btn-primary"
                    aria-label="@lang('link.edit')">
                    <x-icon.edit class="mr-2"/>
                    <span class="d-none d-sm-inline">@lang('linkace.edit')</span>
                </a>
                <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                    class="btn btn-sm btn-outline-danger cursor-pointer" aria-label="@lang('link.delete')">
                    <x-icon.trash class="mr-2"/>
                    <span class="d-none d-sm-inline">@lang('linkace.delete')</span>
                </a>
            </div>
            <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                action="{{ route('links.destroy', [$link->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="link_id" value="{{ $link->id }}">
            </form>

            <div class="mb-3">
                <a href="{{ waybackLink($link) }}" class="btn btn-sm btn-block btn-outline-warning" target="_blank">
                    @lang('link.wayback')
                </a>
            </div>

            <form action="{{ route('links.toggle-check', [$link->id]) }}" method="POST"
                class="mb-2 d-flex align-items-center">
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

            @if($link->status !== 1)
                <form action="{{ route('links.mark-working', [$link->id]) }}" method="POST"
                    class="mt-2 d-flex align-items-center">
                    @csrf
                    <small class="mr-3">@lang('link.status_is_broken')</small>
                    <input type="hidden" name="toggle" value="0">
                    <button type="submit" class="btn btn-xs btn-outline-secondary ml-auto">
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
                            <a href="{{ route('lists.show', [$list->id]) }}" class="badge badge-primary badge-pill">
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

        <div class="small text-muted">
            @foreach($history as $entry)
                @if($loop->index === 5 && $loop->count >= 10)
                    <a data-toggle="collapse" href="#link-history" role="button" class="d-inline-block mb-1"
                        aria-expanded="false" aria-controls="link-history">
                        @lang('linkace.more')
                        <x-icon.caret-down class="fw"/>
                    </a>
                    <div id="link-history" class="collapse">
                        @endif
                        <x-links.history-entry :entry="$entry"/>
                        @endforeach
                        <div>{{ formatDateTime($link->created_at) }}: @lang('link.history_created')</div>
                        @if(count($history) >= 10)
                    </div>
                @endif
        </div>
    </div>

@endsection
