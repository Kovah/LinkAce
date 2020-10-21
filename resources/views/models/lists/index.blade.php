@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('list.lists')
        </h3>
        <a href="{{ route('lists.create') }}" class="btn btn-sm btn-primary ml-auto" aria-label="@lang('link.add')">
            <x-icon.plus class="mr-2"/>
            @lang('linkace.add')
        </a>
    </header>

    @if(!$lists->isEmpty())

        <div class="row my-3">
            @foreach($lists as $list)
                @include('models.lists.partials.single')
            @endforeach
        </div>

    @else

        <div class="alert alert-info m-3">
            @lang('linkace.no_results_found', ['model' => trans('list.lists')])
        </div>

    @endif

    @if(!$lists->isEmpty())
        {!! $lists->onEachSide(1)->links() !!}
    @endif

@endsection
