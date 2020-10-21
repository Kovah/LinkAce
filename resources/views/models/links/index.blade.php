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
            <button  type="button" id="link-index-order-dd"
                class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @lang('search.order_by') <x-icon.caret-down class="ml-1"/>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="link-index-order-dd">
                <a class="dropdown-item small"
                    href="{{ route('links.index', ['orderBy' => 'created_at', 'orderDir' => 'asc']) }}">
                    @lang('search.order_by.created_at:asc')
                </a>
                <a class="dropdown-item small"
                    href="{{ route('links.index', ['orderBy' => 'created_at', 'orderDir' => 'desc']) }}">
                    @lang('search.order_by.created_at:desc')
                </a>
                <a class="dropdown-item small"
                    href="{{ route('links.index', ['orderBy' => 'url', 'orderDir' => 'asc']) }}">
                    @lang('search.order_by.url:asc')
                </a>
                <a class="dropdown-item small"
                    href="{{ route('links.index', ['orderBy' => 'url', 'orderDir' => 'desc']) }}">
                    @lang('search.order_by.url:desc')
                </a>
                <a class="dropdown-item small"
                    href="{{ route('links.index', ['orderBy' => 'title', 'orderDir' => 'asc']) }}">
                    @lang('search.order_by.title:asc')
                </a>
                <a class="dropdown-item small"
                    href="{{ route('links.index', ['orderBy' => 'title', 'orderDir' => 'desc']) }}">
                    @lang('search.order_by.title:desc')
                </a>
            </div>
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
