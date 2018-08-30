@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('category.categories')
            </p>
            <a href="{{ route('categories.create') }}" class="card-header-icon" aria-label="@lang('category.add')">
                <div class="icon">
                    <i class="fa fa-plus fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.add')
            </a>
        </header>
        <div class="card-content">

            @if(!$categories->isEmpty())

                @include('models.categories._table')

            @else

                <div class="message is-warning">
                    <div class="message-body">
                        @lang('linkace.no_results_found', ['model' => trans('category.categories')])
                    </div>
                </div>

            @endif

        </div>
        @if(!$categories->isEmpty())
            {!! $categories->links('partials.card-pagination', ['paginator' => $categories]) !!}
        @endif
    </div>

@endsection
