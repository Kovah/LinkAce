@extends('layouts.guest')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('category.categories')
        </h3>
    </header>

    <div class="card my-3">
        <div class="card-table">

            @if(!$categories->isEmpty())

                @include('guest.categories._table')

            @else

                <div class="alert alert-info m-3">
                    @lang('linkace.no_results_found', ['model' => trans('category.categories')])
                </div>

            @endif

        </div>
    </div>

    @if(!$categories->isEmpty())
        {!! $categories->links() !!}
    @endif

@endsection
