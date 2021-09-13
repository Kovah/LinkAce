@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('tag.tags')
        </h3>

        <div class="form-group ml-auto">

            <form action="{{ route('tags.index') }}" method="GET">
                <div class="input-group input-group-sm">
                    <label for="filter" class="sr-only">@lang('tag.filter_tags')</label>
                        <input type="text" name="filter" id="filter" autofocus minlength="1"
                            class="form-control mr-2" placeholder="@lang('tag.filter_tags')" 
                            value="{{ request()->input('filter') }}" />
                        <a href="{{ route('tags.index') }}" class="btn btn-sm bg-transparent" style="margin-left: -40px; z-index: 100;">
                            <x-icon.ban />
                        </a>
                    <div class="input-group-append mr-2">
                        <button class="btn btn-primary" type="submit">
                            <x-icon.search />
                        </button>
                    </div>
                    <a href="{{ route('tags.create') }}" class="btn btn-sm btn-primary" aria-label="@lang('link.add')">
                        <x-icon.plus class="mr-2"/>
                        @lang('linkace.add')
                    </a>
                </div>
            </form>
        </div>
    </header>

    <div class="card my-3">
        <div class="card-table">

            @if($tags->isNotEmpty())

                @include('models.tags.partials.table')

            @else

                <div class="alert alert-info m-3">
                    @lang('linkace.no_results_found', ['model' => trans('tag.tags')])
                </div>

            @endif

        </div>
    </div>

    @if($tags->isNotEmpty())
        {!! $tags->onEachSide(1)->appends(['orderBy' => $orderBy, 'orderDir' => $orderDir, 'filter' => $filter])->links() !!}
    @endif

@endsection
