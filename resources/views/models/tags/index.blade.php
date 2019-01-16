@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('link.links')
        </h3>
        <a href="{{ route('tags.create') }}" class="btn btn-sm btn-primary ml-auto" aria-label="@lang('link.add')">
            <i class="fa fa-plus fa-mr"></i>
            @lang('linkace.add')
        </a>
    </header>

    <div class="card my-3">
        <div class="card-table">

            @if(!$tags->isEmpty())

                @include('models.tags.partials.table')

            @else

                <div class="alert alert-info m-3">
                    @lang('linkace.no_results_found', ['model' => trans('tag.tags')])
                </div>

            @endif

        </div>
    </div>

    @if(!$tags->isEmpty())
        {!! $tags->links() !!}
    @endif

@endsection
