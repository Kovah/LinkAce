@extends('layouts.guest')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">

            <span class="me-3">@lang('list.list')</span>
            <a href="{{ route('guest.lists.links.feed', ['list' => $list]) }}"
                class="ms-auto btn btn-xs btn-outline-secondary">
                <x-icon.feed class="fw"/>
                <span class="visually-hidden">@lang('linkace.feed')</span>
            </a>

        </header>
        <div class="card-body">

            <h2 class="mb-0">{{ $list->name }}</h2>

            @if($list->description)
                <p class="mt-2 mb-0">{{ $list->description }}</p>
            @endif

        </div>
    </div>

    <div class="card my-3">
        <div class="card-header">
            @lang('link.links')
        </div>
        <div class="card-table">

            @include('guest.links.partials.table', ['links' => $listLinks])

        </div>
    </div>

    {!! $listLinks->onEachSide(1)->withQueryString()->links() !!}

@endsection
