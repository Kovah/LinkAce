@extends('layouts.guest')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('list.lists')
        </h3>
    </header>

    <div class="card my-3">
        <div class="card-table">

            @if(!$lists->isEmpty())

                @include('guest.lists.partials.table')

            @else

                <div class="alert alert-info m-3">
                    @lang('linkace.no_results_found', ['model' => trans('list.lists')])
                </div>

            @endif

        </div>
    </div>

    @if(!$lists->isEmpty())
        {!! $lists->links() !!}
    @endif

@endsection
