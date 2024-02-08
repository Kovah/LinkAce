@extends('layouts.guest')

@push('html-header')
    <link rel="alternate" type="application/atom+xml" href="{{ route('guest.links.feed') }}">
@endpush

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0 me-3">
            @lang('link.links')
        </h3>
        <x-models.link-display-toggles class="ms-auto"/>
        <a href="{{ route('guest.links.feed') }}" class="ms-3 btn btn-sm btn-outline-secondary">
            <x-icon.feed class="fw"/>
            <span class="visually-hidden">@lang('linkace.add')</span>
        </a>
        <x-models.link-order-dropdown class="ms-1"/>
    </header>

    <section class="my-4">
        @if($links->isNotEmpty())

            <div class="link-wrapper">
                @if(session('link_display_mode') === Link::DISPLAY_CARDS)
                    @include('guest.links.partials.list-cards')
                @elseif(session('link_display_mode') === Link::DISPLAY_LIST_SIMPLE)
                    @include('guest.links.partials.list-simple')
                @else
                    @include('guest.links.partials.list-detailed')
                @endif
            </div>

        @else

            <div class="alert alert-info">
                @lang('linkace.no_results_found', ['model' => trans('link.links')])
            </div>

        @endif
    </section>

    @if($links->isNotEmpty())
        {!! $links->onEachSide(1)->withQueryString()->links() !!}
    @endif

@endsection
