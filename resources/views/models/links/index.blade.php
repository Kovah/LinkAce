@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('link.links')
        </h3>

        <div class="btn-group ml-auto">
            <a href="{{ route('links.create') }}" class="btn btn-sm btn-primary"
                aria-label="@lang('link.add')">
                <x-icon.plus class="mr-2"/>
                @lang('linkace.add')
            </a>
            @include('models.links.partials.index-order-dropdown', ['baseRoute' => 'links.index'])
        </div>
    </header>

    <section class="my-4">
        @if($links->isNotEmpty())

            <div class="link-wrapper">
                @if((int)usersettings('link_display_mode') === Link::DISPLAY_CARDS)
                    @include('models.links.partials.list-cards')
                @elseif((int)usersettings('link_display_mode') === Link::DISPLAY_LIST_SIMPLE)
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
        {!! $links->onEachSide(1)->appends(['orderBy' => $orderBy, 'orderDir' => $orderDir])->links() !!}
    @endif

@endsection
