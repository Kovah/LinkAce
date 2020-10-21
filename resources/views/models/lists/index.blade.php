@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('list.lists')
        </h3>
        <div class="btn-group ml-auto">
            <a href="{{ route('lists.create') }}" class="btn btn-sm btn-primary" aria-label="@lang('list.add')">
                <x-icon.plus class="mr-2"/>
                @lang('linkace.add')
            </a>
            <button  type="button" id="list-index-order-dd"
                class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @lang('search.order_by') <x-icon.caret-down class="ml-1"/>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="list-index-order-dd">
                <a class="dropdown-item small"
                    href="{{ route('lists.index', ['orderBy' => 'created_at', 'orderDir' => 'asc']) }}">
                    @lang('search.order_by.created_at:asc')
                </a>
                <a class="dropdown-item small"
                    href="{{ route('lists.index', ['orderBy' => 'created_at', 'orderDir' => 'desc']) }}">
                    @lang('search.order_by.created_at:desc')
                </a>
                <a class="dropdown-item small"
                    href="{{ route('lists.index', ['orderBy' => 'name', 'orderDir' => 'asc']) }}">
                    @lang('search.order_by.title:asc')
                </a>
                <a class="dropdown-item small"
                    href="{{ route('lists.index', ['orderBy' => 'name', 'orderDir' => 'desc']) }}">
                    @lang('search.order_by.title:desc')
                </a>
                <a class="dropdown-item small"
                    href="{{ route('lists.index', ['orderBy' => 'links_count', 'orderDir' => 'asc']) }}">
                    @lang('search.order_by.number_links:asc')
                </a>
                <a class="dropdown-item small"
                    href="{{ route('lists.index', ['orderBy' => 'links_count', 'orderDir' => 'desc']) }}">
                    @lang('search.order_by.number_links:desc')
                </a>
            </div>
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
