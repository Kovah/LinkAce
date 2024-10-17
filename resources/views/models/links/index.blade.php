@extends('layouts.app')

@section('content')

    <header class="links-header d-flex align-items-center">
        <h3 class="mb-0">
            @lang('link.links')
        </h3>
        <x-models.link-display-toggles class="ms-auto"/>
        <div class="btn-group ms-3">
            <a href="{{ route('links.create') }}" class="btn btn-sm btn-primary"
                aria-label="@lang('link.add')">
                <x-icon.plus class="me-2"/>
                @lang('linkace.add')
            </a>
            <x-models.link-order-dropdown :without-wrapper="true"/>
        </div>
    </header>

    <section class="link-listing mb-4">
        @if($links->isNotEmpty())

            <div class="link-wrapper">
                @if(usersettings('link_display_mode') === Link::DISPLAY_CARDS)
                    @include('models.links.partials.list-cards')
                @elseif(usersettings('link_display_mode') === Link::DISPLAY_LIST_SIMPLE)
                    @include('models.links.partials.list-simple')
                @else
                    @include('models.links.partials.list-detailed')
                @endif
            </div>

        @else

            <div class="alert alert-info mt-4">
                @lang('linkace.no_results_found', ['model' => trans('link.links')])
            </div>

        @endif
    </section>

    @if($links->isNotEmpty())
        {!! $links->onEachSide(1)->withQueryString()->links() !!}
    @endif

@endsection
