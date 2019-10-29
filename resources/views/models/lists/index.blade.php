@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('link.links')
        </h3>
        <a href="{{ route('lists.create') }}" class="btn btn-sm btn-primary ml-auto" aria-label="@lang('link.add')">
            <i class="fas fa-plus mr-2"></i>
            @lang('linkace.add')
        </a>
    </header>

    <div class="card my-3">
        <div class="card-table">

            @if(!$lists->isEmpty())

                @include('models.lists.partials.table')

            @else

                <div class="alert alert-info m-3">
                    @lang('linkace.no_results_found', ['model' => trans('list.lists')])
                </div>

            @endif

        </div>
    </div>

    @if(!$lists->isEmpty())
        {!! $lists->links() !!}
    @endif

@endsection
