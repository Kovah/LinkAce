@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('tag.tags')
        </h3>
        <a href="{{ route('tags.create') }}" class="btn btn-sm btn-primary ml-auto" aria-label="@lang('link.add')">
            <x-icon.plus class="mr-2"/>
            @lang('linkace.add')
        </a>
    </header>

    <div class="card my-3">
        <div class="card-table">

            @if($tags->isNotEmpty())

                @include('models.tags.partials.table')

            @else

                <div class="alert alert-info m-3">
                    @lang('linkace.no_results_found', ['model' => trans('tag.tags')])
                </div>

            @endif

        </div>
    </div>

    @if($tags->isNotEmpty())
        {!! $tags->onEachSide(1)->appends(['orderBy' => $orderBy, 'orderDir' => $orderDir])->links() !!}
    @endif

@endsection
