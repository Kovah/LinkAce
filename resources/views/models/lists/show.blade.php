@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">
            <span class="me-3">
                <x-models.visibility-badge :model="$list" class="d-inline-block me-1"/>
                @lang('list.list')
            </span>
            <div class="ms-auto">
                <a href="{{ route('lists.edit', [$list->id]) }}" class="btn btn-sm btn-primary"
                    aria-label="@lang('list.edit')">
                    <x-icon.edit class="me-2"/>
                    @lang('linkace.edit')
                </a>
                <a onclick="event.preventDefault();document.getElementById('list-delete-{{ $list->id }}').submit();"
                    class="btn btn-sm btn-outline-danger" aria-label="@lang('list.delete')">
                    <x-icon.trash class="me-2"/>
                    @lang('linkace.delete')
                </a>
            </div>
            <form id="list-delete-{{ $list->id }}" method="POST" style="display: none;"
                action="{{ route('lists.destroy', [$list->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="list_id" value="{{ $list->id }}">
            </form>
        </header>
        <div class="card-body">

            <h2 class="mb-0">{{ $list->name }}</h2>

            @if($list->description)
                <div class="mt-2 mb-0">{!! $list->formatted_description !!}</div>
            @endif

        </div>
    </div>

    <div class="card my-3">
        <div class="card-header">
            @lang('link.links')
        </div>
        <div class="card-table">
            @include('models.links.partials.table', ['links' => $listLinks])
        </div>
    </div>

    {!! $listLinks->onEachSide(1)->withQueryString()->links() !!}

    <div class="list-history mt-5">
        <h3 class="h6 mb-2">@lang('linkace.history')</h3>

        <div class="history small text-muted">
            @foreach($history as $entry)
                @if($loop->index === 5 && $loop->count >= 10)
                    <a data-bs-toggle="collapse" href="#list-history" role="button" class="d-inline-block mb-1"
                        aria-expanded="false" aria-controls="list-history">
                        @lang('linkace.more')
                        <x-icon.caret-down class="fw"/>
                    </a>
                    <div id="list-history" class="collapse">
                @endif
                <x-history.list-entry :entry="$entry"/>
            @endforeach
            <div>{{ formatDateTime($list->created_at) }}: @lang('list.history_created')</div>
            @if(count($history) >= 10)
                </div>
            @endif
        </div>
    </div>

@endsection
