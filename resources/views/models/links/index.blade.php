@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('link.links')
            </p>
            <a href="{{ route('links.create') }}" class="card-header-icon" aria-label="@lang('link.add')">
                <div class="icon">
                    <i class="fa fa-plus fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.add')
            </a>
        </header>
        <div class="card-content">

            @if(!$links->isEmpty())

                @include('models.links._table')

            @else

                <div class="message is-warning">
                    <div class="message-body">
                        @lang('linkace.no_results_found', ['model' => trans('link.links')])
                    </div>
                </div>

            @endif

        </div>
        @if(!$links->isEmpty())
            {!! $links->links('partials.card-pagination', ['paginator' => $links]) !!}
        @endif
    </div>

@endsection
