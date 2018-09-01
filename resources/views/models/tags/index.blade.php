@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('tag.tags')
            </p>
            <a href="{{ route('tags.create') }}" class="card-header-icon" aria-label="@lang('tag.add')">
                <div class="icon">
                    <i class="fa fa-plus fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.add')
            </a>
        </header>
        <div class="card-content">

            @if(!$tags->isEmpty())

                @include('models.tags._table')

            @else

                <div class="message is-warning">
                    <div class="message-body">
                        @lang('linkace.no_results_found', ['model' => trans('tag.tags')])
                    </div>
                </div>

            @endif

        </div>
        @if(!$tags->isEmpty())
            {!! $tags->links('partials.card-pagination', ['paginator' => $tags]) !!}
        @endif
    </div>

@endsection
