@extends('layouts.guest')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">

            <span class="me-3">@lang('list.list')</span>
            <a href="{{ route('guest.lists.links.feed', ['list' => $list]) }}"
                class="ms-auto btn btn-xs btn-outline-secondary">
                <x-icon.feed class="fw"/>
                <span class="visually-hidden">@lang('linkace.feed')</span>
            </a>

        </header>
        <div class="card-body">

            <h2 class="mb-0">{{ $list->name }}</h2>

            @if($list->description)
                <p class="mt-2 mb-0">{{ $list->description }}</p>
            @endif

        </div>
    </div>

    <section class="my-4">
        @if($links->isNotEmpty())
            <div class="d-flex align-items-center mb-4">
                <x-models.link-display-toggles class="ms-auto"/>
                <x-models.link-order-dropdown class="ms-3"/>
            </div>
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
