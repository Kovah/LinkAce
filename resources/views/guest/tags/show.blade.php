@extends('layouts.guest')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">

            <span class="mr-3">@lang('tag.tag')</span>
            <a href="{{ route('guest.tags.links.feed', ['tag' => $tag]) }}"
                class="ml-auto btn btn-xs btn-outline-secondary">
                <x-icon.feed/>
                <span class="sr-only">@lang('linkace.feed')</span>
            </a>

        </header>
        <div class="card-body">
            <h2 class="mb-0">{{ $tag->name }}</h2>
        </div>
    </div>

    <div class="card mt-3 mb-3">
        <div class="card-header">
            @lang('link.links')
        </div>
        <div class="card-table">

            @include('guest.links.partials.table', ['links' => $tagLinks])

        </div>
    </div>

    {!! $tagLinks->onEachSide(1)->appends(['orderBy' => $orderBy, 'orderDir' => $orderDir])->links() !!}

@endsection
