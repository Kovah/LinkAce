@extends('layouts.app')

@section('content')

    <header class="tags-header d-flex align-items-center">
        <h3 class="mb-0 me-3">
            @lang('tag.tags')
        </h3>

        <div class="mb-0 ms-auto">
            <form action="{{ route('tags.index') }}" method="GET" class="d-flex flex-column flex-sm-row">
                <label for="filter" class="visually-hidden">@lang('tag.filter_tags')</label>
                <div class="input-group input-group-sm mb-1 mb-sm-0 me-sm-2">
                    <input type="text" name="filter" id="filter" minlength="1"
                        class="form-control" placeholder="@lang('tag.filter_tags')"
                        value="{{ request()->input('filter') }}"/>
                    <a href="{{ route('tags.index') }}" class="btn btn-sm btn-outline-primary">
                        <x-icon.ban/>
                    </a>
                    <button class="btn btn-primary" type="submit" title="@lang('list.filter_lists')">
                        <x-icon.search/>
                    </button>
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

    <div class="tags-listing card my-3">
        <div class="card-table">

            @if($tags->isNotEmpty())
                @include('models.tags.partials.table')
            @else
                <div class="alert alert-info mt-4">
                    @lang('linkace.no_results_found', ['model' => trans('tag.tags')])
                </div>
            @endif

        </div>
    </div>

    @if($tags->isNotEmpty())
        {!! $tags->onEachSide(1)->withQueryString()->links() !!}
    @endif

@endsection
