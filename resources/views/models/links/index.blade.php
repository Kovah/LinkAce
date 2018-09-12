@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center justify-content-between">
        <h3 class="mb-0">
            @lang('link.links')
        </h3>
        <a href="{{ route('links.create') }}" class="btn btn-sm btn-primary" aria-label="@lang('link.add')">
            <i class="fa fa-plus fa-mr"></i>
            @lang('linkace.add')
        </a>
    </header>

    <section class="link-wrapper my-3">
        @if(!$links->isEmpty())

            @foreach($links as $link)
                @include('models.links._single')
            @endforeach

        @else

            <div class="alert alert-info">
                @lang('linkace.no_results_found', ['model' => trans('link.links')])
            </div>

        @endif
    </section>

    @if(!$links->isEmpty())
        {!! $links->links('partials.card-pagination', ['paginator' => $links]) !!}
    @endif

@endsection
