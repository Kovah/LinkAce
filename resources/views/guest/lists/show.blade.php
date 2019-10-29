@extends('layouts.guest')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('list.list')
        </header>
        <div class="card-body">

            <div class="d-flex align-items-center">
                @if($list->parentList)
                    <p class="mr-2 mb-0">
                        <a href="{{ route('guest.lists.show', [$list->parentList->id]) }}"
                            class="badge badge-primary">
                            {{ $list->parentList->name }} &leftarrow;
                        </a>
                    </p>
                @endif
                <h2 class="mb-0">{{ $list->name }}</h2>
            </div>

            <div class="row">
                @if($list->description)
                    <div class="col mt-3">
                        {{ $list->description }}
                    </div>
                @endif

                @if(!$list->childlists->isEmpty())
                    <div class="col mt-3">
                        <label>@lang('list.lists')</label>
                        <br>
                        @foreach($list->childlists as $list)
                            <a href="{{ route('guest.lists.show', [$list->id]) }}" class="badge badge-light">
                                {{ $list->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>

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

    {!! $list_links->links() !!}

@endsection
