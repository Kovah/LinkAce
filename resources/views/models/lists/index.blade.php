@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0 mr-3">
            @lang('list.lists')
        </h3>
        <div class="btn-group ml-auto">
            <a href="{{ route('lists.create') }}" class="btn btn-sm btn-primary" aria-label="@lang('list.add')">
                <x-icon.plus class="mr-2"/>
                @lang('linkace.add')
            </a>
            @include('models.lists.partials.index-order-dropdown', ['baseRoute' => 'lists.index'])
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
