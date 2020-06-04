@extends('layouts.guest')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('list.lists')
        </h3>
    </header>

    @if(!$lists->isEmpty())

        <div class="row my-3">
            @foreach($lists as $list)
                @include('guest.lists.partials.single')
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
