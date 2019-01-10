@extends('layouts.guest')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">
            <span class="mr-3">
                @lang('tag.tag')
            </span>
            <div class="ml-auto">
                @lang('tag.author', ['user' => $tag->user->name])
            </div>
        </header>
        <div class="card-body">

            <h2 class="mb-0">{{ $tag->name }}</h2>

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            @lang('link.links')
        </div>
        <div class="card-table">

            @include('guest.links._table', ['links' => $tag_links])

        </div>
    </div>

    {!! $tag_links->links() !!}

@endsection
