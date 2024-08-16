@extends('layouts.app')

@section('content')

    <header class="lists-header d-flex align-items-center">
        <h3 class="mb-0 me-3">
            @lang('list.lists')
        </h3>

        <div class="mb-0 ms-auto">
            <form action="{{ route('lists.index') }}" method="GET" class="d-flex flex-column flex-sm-row">
                <label for="filter" class="visually-hidden">@lang('list.filter_lists')</label>
                <div class="input-group input-group-sm mb-1 mb-sm-0 me-sm-2">
                    <input type="text" name="filter" id="filter" minlength="1"
                        class="form-control" placeholder="@lang('list.filter_lists')"
                        value="{{ request()->input('filter') }}"/>
                    <a href="{{ route('lists.index') }}" class="btn btn-sm btn-outline-primary">
                        <x-icon.ban/>
                    </a>
                    <button class="btn btn-primary" type="submit" title="@lang('list.filter_lists')">
                        <x-icon.search/>
                    </button>
                </div>
                <div class="btn-group ms-auto ms-sm-0 flex-sm-shrink-0">
                    <a href="{{ route('lists.create') }}" class="btn btn-sm btn-primary" aria-label="@lang('list.add')">
                        <x-icon.plus class="me-2"/>
                        @lang('linkace.add')
                    </a>
                    @include('models.lists.partials.index-order-dropdown', ['baseRoute' => 'lists.index'])
                </div>
            </form>
        </div>

    </header>

    @if($lists->isNotEmpty())

        <div class="list-listing bulk-edit" data-type="lists">
            <form class="bulk-edit-form visually-hidden text-end" action="{{ route('bulk-edit.form') }}" method="POST">
                @csrf()
                <input type="hidden" name="type">
                <input type="hidden" name="models">
                <div class="btn-group mt-1">
                    <button type="button" class="bulk-edit-submit btn btn-outline-primary btn-xs">Edit</button>
                    <button type="button" class="bulk-edit-select-all btn btn-outline-primary btn-xs">Select all</button>
                </div>
            </form>
            <div class="row mt-3">
                @foreach($lists as $list)
                    @include('models.lists.partials.single')
                @endforeach
            </div>
        </div>

    @else

        <div class="alert alert-info m-3">
            @lang('linkace.no_results_found', ['model' => trans('list.lists')])
        </div>

    @endif

    @if($lists->isNotEmpty())
        {!! $lists->onEachSide(1)->withQueryString()->links() !!}
    @endif

@endsection
