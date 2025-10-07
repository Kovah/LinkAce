@extends('layouts.guest')

@push('html-header')
    <link rel="alternate" type="application/atom+xml" href="{{ route('guest.tags.feed') }}">
@endpush

@section('content')

    <header class="tags-header d-flex align-items-center">
        <h3 class="mb-0 me-3">
            @lang('tag.tags')
        </h3>
        <div class="mb-0 ms-auto">
            <div class="d-flex flex-column flex-sm-row gap-3">
                <form action="{{ route('guest.tags.index') }}" method="GET">
                    <label for="filter" class="visually-hidden">@lang('tag.filter_tags')</label>
                    <div class="input-group input-group-sm mb-1 mb-sm-0 me-sm-2">
                        <input type="text" name="filter" id="filter" minlength="1"
                            class="form-control" placeholder="@lang('tag.filter_tags')"
                            value="{{ request()->input('filter') }}"/>
                        <a href="{{ route('guest.tags.index') }}" class="btn btn-sm btn-outline-primary">
                            <x-icon.ban/>
                        </a>
                        <button class="btn btn-primary" type="submit" title="@lang('list.filter_lists')">
                            <x-icon.search/>
                        </button>
                    </div>
                </form>
                <a href="{{ route('guest.tags.feed') }}" class="ms-auto btn btn-sm btn-outline-secondary">
                    <x-icon.feed/>
                    <span class="visually-hidden">@lang('linkace.feed')</span>
                </a>
            </div>
        </div>
    </header>

    <div class="tags-listing card my-3 mb-3">
        <div class="card-table">

            @if(!$tags->isEmpty())
                @include('guest.tags.partials.table')
            @else
                <div class="alert alert-info m-3">
                    @lang('linkace.no_results_found', ['model' => trans('tag.tags')])
                </div>
            @endif

        </div>
    </div>

    @if(!$tags->isEmpty())
        {!! $tags->onEachSide(1)->withQueryString()->links() !!}
    @endif

@endsection
