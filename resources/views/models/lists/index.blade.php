@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0 mr-3">
            @lang('list.lists')
        </h3>

        <div class="form-group mb-0 ml-auto">
            <form action="{{ route('lists.index') }}" method="GET" class="d-flex flex-column flex-sm-row">
                <div class="input-group input-group-sm mb-1 mb-sm-0">
                    <label for="filter" class="sr-only">@lang('list.filter_lists')</label>
                    <input type="text" name="filter" id="filter" minlength="1"
                        class="form-control" placeholder="@lang('list.filter_lists')"
                        value="{{ request()->input('filter') }}"/>
                    <a href="{{ route('lists.index') }}" class="btn btn-sm bg-secondary">
                        <x-icon.ban/>
                    </a>
                    <div class="input-group-append mr-sm-2">
                        <button class="btn btn-primary" type="submit" title="@lang('list.filter_lists')">
                            <x-icon.search/>
                        </button>
                    </div>
                </div>
                <div class="btn-group ml-auto ml-sm-0 flex-sm-shrink-0">
                    <a href="{{ route('lists.create') }}" class="btn btn-sm btn-primary" aria-label="@lang('list.add')">
                        <x-icon.plus class="mr-2"/>
                        @lang('linkace.add')
                    </a>
                    @include('models.lists.partials.index-order-dropdown', ['baseRoute' => 'lists.index'])
                </div>
            </form>
        </div>

    </header>

    @if($lists->isNotEmpty())

        <div class="row mt-3">
            @foreach($lists as $list)
                @include('models.lists.partials.single')
            @endforeach
        </div>

    @else

        <div class="alert alert-info m-3">
            @lang('linkace.no_results_found', ['model' => trans('list.lists')])
        </div>

    @endif

    @if($lists->isNotEmpty())
        {!! $lists->onEachSide(1)->appends(['orderBy' => $orderBy, 'orderDir' => $orderDir])->links() !!}
    @endif

@endsection
