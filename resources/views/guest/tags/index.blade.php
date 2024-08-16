@extends('layouts.guest')

@push('html-header')
    <link rel="alternate" type="application/atom+xml" href="{{ route('guest.tags.feed') }}">
@endpush

@section('content')

    <header class="tags-header d-flex align-items-center">
        <h3 class="mb-0 me-3">
            @lang('tag.tags')
        </h3>
        <a href="{{ route('guest.tags.feed') }}" class="ms-auto btn btn-sm btn-outline-secondary">
            <x-icon.feed/>
            <span class="visually-hidden">@lang('linkace.feed')</span>
        </a>
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
