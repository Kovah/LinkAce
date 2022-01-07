@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0 me-3">
            @lang('tag.tags')
        </h3>

        <div class="form-group mb-0 ms-auto">
            <form action="{{ route('tags.index') }}" method="GET" class="d-flex flex-column flex-sm-row">
                <div class="input-group input-group-sm mb-1 mb-sm-0">
                    <label for="filter" class="visually-hidden">@lang('tag.filter_tags')</label>
                    <input type="text" name="filter" id="filter" minlength="1"
                        class="form-control" placeholder="@lang('tag.filter_tags')"
                        value="{{ request()->input('filter') }}"/>
                    <a href="{{ route('tags.index') }}" class="btn btn-sm bg-secondary">
                        <x-icon.ban/>
                    </a>
                    <div class="input-group-append me-sm-2">
                        <button class="btn btn-primary" type="submit" title="@lang('list.filter_lists')">
                            <x-icon.search/>
                        </button>
                    </div>
                </div>
                <div class="btn-group ms-auto ms-sm-0 flex-sm-shrink-0">
                    <a href="{{ route('tags.create') }}" class="btn btn-sm btn-primary" aria-label="@lang('tag.add')">
                        <x-icon.plus class="me-2"/>
                        @lang('linkace.add')
                    </a>
                    @include('models.tags.partials.index-order-dropdown', ['baseRoute' => 'tags.index'])
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
