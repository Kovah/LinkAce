@extends('layouts.guest')

@push('html-header')
    <link rel="alternate" type="application/atom+xml" href="{{ route('guest.links.feed') }}">
@endpush

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0 me-3">
            @lang('link.links')
        </h3>
        <a href="{{ route('guest.links.feed') }}" class="ms-auto btn btn-sm btn-outline-secondary">
            <x-icon.feed/>
            <span class="visually-hidden">@lang('linkace.add')</span>
        </a>
        <div class="dropdown ms-1">
            @include('models.links.partials.index-order-dropdown', ['baseRoute' => 'guest.links.index'])
        </div>
    </header>

    <section class="my-4">
        @if($links->isNotEmpty())

            <div class="link-wrapper">
                @if((int)systemsettings('guest_link_display_mode') === Link::DISPLAY_CARDS)
                    @include('models.links.partials.list-cards')
                @elseif((int)systemsettings('guest_link_display_mode') === Link::DISPLAY_LIST_SIMPLE)
                    @include('models.links.partials.list-simple')
                @else
                    @include('models.links.partials.list-detailed')
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
