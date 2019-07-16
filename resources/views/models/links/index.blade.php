@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('link.links')
        </h3>
        <a href="{{ route('links.create') }}" class="btn btn-sm btn-primary ml-auto" aria-label="@lang('link.add')">
            <i class="fas fa-plus mr-2"></i>
            @lang('linkace.add')
        </a>
    </header>

    <section class="link-wrapper my-3">
        @if(!$links->isEmpty())

            @foreach($links as $link)
                @include('models.links.partials.single')
            @endforeach

        @else

            <div class="alert alert-info">
                @lang('linkace.no_results_found', ['model' => trans('link.links')])
            </div>

        @endif
    </section>

    @if(!$links->isEmpty())
        {!! $links->links() !!}
    @endif

@endsection
