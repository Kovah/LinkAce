@extends('layouts.app')

@section('content')

    <header class="d-flex align-items-center">
        <h3 class="mb-0">
            @lang('link.links')
        </h3>
        <a href="{{ route('links.create') }}" class="btn btn-sm btn-primary ml-auto" aria-label="@lang('link.add')">
            <i class="fas fa-plus mr-2"></i>
            @lang('linkace.add')
        </a>
    </header>

    <section class="my-3">
        @if(!$links->isEmpty())

            <div class="link-wrapper {{ usersettings('link_display_mode') === '1' ? 'row' : '' }}">
                @foreach($links as $link)
                    @if(usersettings('link_display_mode') === '1')
                        @include('models.links.partials.single-cards')
                    @else
                        @include('models.links.partials.single')
                    @endif
                @endforeach
            </div>

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
