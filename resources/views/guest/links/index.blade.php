@extends('layouts.guest')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('link.links')
        </h3>
    </header>

    <section class="link-wrapper my-3">
        @if(!$links->isEmpty())

            @foreach($links as $link)
                @include('guest.links._single')
            @endforeach

        @else

            <div class="alert alert-info">
                @lang('linkace.no_results_found', ['model' => trans('link.links')])
            </div>

        @endif
    </section>

    @if(!$links->isEmpty())
        {!! $links->links() !!}
    @endif

@endsection
