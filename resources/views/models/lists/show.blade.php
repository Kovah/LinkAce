@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">
            <span class="me-3">
                <x-models.visibility-badge :model="$list" class="d-inline-block me-1"/>
                @lang('list.list')
            </span>
            <div class="ms-auto">
                <a href="{{ route('lists.edit', ['list' => $list]) }}" class="btn btn-sm btn-primary"
                    aria-label="@lang('list.edit')">
                    <x-icon.edit class="me-2"/>
                    @lang('linkace.edit')
                </a>
                <a onclick="event.preventDefault();document.getElementById('list-delete-{{ $list->id }}').submit();"
                    class="btn btn-sm btn-outline-danger" aria-label="@lang('list.delete')">
                    <x-icon.trash class="me-2"/>
                    @lang('linkace.delete')
                </a>
            </div>
            <form id="list-delete-{{ $list->id }}" method="POST" style="display: none;"
                action="{{ route('lists.destroy', ['list' => $list]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="list_id" value="{{ $list->id }}">
            </form>
        </header>
        <div class="card-body">

            <h2 class="mb-0">{{ $list->name }}</h2>

            <div class="mt-2 small">@lang('linkace.added_by'): <x-models.author :model="$list"/></div>

            @if($list->description)
                <div class="mt-2 mb-0">{!! $list->formatted_description !!}</div>
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
                @if(usersettings('link_display_mode') === Link::DISPLAY_CARDS)
                    @include('models.links.partials.list-cards')
                @elseif(usersettings('link_display_mode') === Link::DISPLAY_LIST_SIMPLE)
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

    <div class="list-history mt-5">
        <h3 class="h6 mb-2">@lang('linkace.history')</h3>

        <div class="history small text-muted">
            @foreach($history as $entry)
                @if($loop->index === 5 && $loop->count >= 10)
                    <a data-bs-toggle="collapse" href="#list-history" role="button" class="d-inline-block mb-1"
                        aria-expanded="false" aria-controls="list-history">
                        @lang('linkace.more')
                        <x-icon.caret-down class="fw"/>
                    </a>
                    <div id="list-history" class="collapse">
                @endif
                <x-history.list-entry :entry="$entry"/>
            @endforeach
            <div>{{ formatDateTime($list->created_at) }}: @lang('list.history_created')</div>
            @if(count($history) >= 10)
                </div>
            @endif
        </div>
    </div>

@endsection
