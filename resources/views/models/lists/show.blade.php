@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">
            <span class="me-3">
                @if($list->is_private)
                    <span>
                        <x-icon.lock class="me-1" title="@lang('list.private')"/>
                        <span class="visually-hidden">@lang('list.private')</span>
                    </span>
                @endif
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

@endsection
