@extends('layouts.guest')

@section('content')

    <div class="tag-details card">
        <header class="card-header d-flex align-items-center">

            <span class="me-3">@lang('tag.tag')</span>
            <a href="{{ route('guest.tags.links.feed', ['tag' => $tag]) }}"
                class="ms-auto btn btn-xs btn-outline-secondary">
                <x-icon.feed class="fw"/>
                <span class="visually-hidden">@lang('linkace.feed')</span>
            </a>

        </header>
        <div class="card-body">
            <h2 class="title mb-0">{{ $tag->name }}</h2>
        </div>
    </div>

    <section class="tag-links my-4">
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
