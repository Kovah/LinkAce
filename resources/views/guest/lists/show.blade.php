@extends('layouts.guest')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('list.list')
        </header>
        <div class="card-body">

            <h2 class="mb-0">{{ $list->name }}</h2>

            @if($list->description)
                <p class="mt-2 mb-0">{{ $list->description }}</p>
            @endif

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            @lang('link.links')
        </div>
        <div class="card-table">

            @include('guest.links.partials.table', ['links' => $list_links])

        </div>
    </div>

    {!! $list_links->onEachSide(1)->links() !!}

@endsection
