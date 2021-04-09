@extends('layouts.guest')

@push('html-header')
    <link rel="alternate" type="application/atom+xml" href="{{ route('guest.lists.feed') }}">
@endpush

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0 mr-3">
            @lang('list.lists')
        </h3>
        <a href="{{ route('guest.lists.feed') }}" class="ml-auto btn btn-sm btn-outline-secondary">
            <x-icon.feed/>
            <span class="sr-only">@lang('linkace.feed')</span>
        </a>
        <div class="dropdown ml-1">
            @include('models.lists.partials.index-order-dropdown', ['baseRoute' => 'lists.index'])
        </div>
    </header>

    @if(!$lists->isEmpty())

        <div class="row my-3">
            @foreach($lists as $list)
                @include('guest.lists.partials.single')
            @endforeach
        </div>

    @else

        <div class="alert alert-info m-3">
            @lang('linkace.no_results_found', ['model' => trans('list.lists')])
        </div>

    @endif

    @if(!$lists->isEmpty())
        {!! $lists->onEachSide(1)->appends(['orderBy' => $orderBy, 'orderDir' => $orderDir])->links() !!}
    @endif

@endsection
