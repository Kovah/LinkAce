@extends('layouts.app')

@section('content')

    <h3 class="mb-4">@lang('user.hello', ['user' => auth()->user()->name])</h3>

    <div class="card">
        <div class="card-header">
            @lang('link.add_quick')
        </div>
        <div class="card-body">

            <form action="{{ route('links.store') }}" method="POST">
                @csrf

                <input type="hidden" name="is_private" value="{{ usersettings('private_default') ?: 0 }}">
                <input type="hidden" name="title" value="">
                <input type="hidden" name="description" value="">

                <div class="input-group">
                    <input type="text" id="url" name="url" required
                        class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                        placeholder="@lang('link.url')" value="{{ old('url') }}"
                        aria-label="@lang('link.url')">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-plus fa-mr"></i> @lang('linkace.add')
                        </button>
                    </div>
                </div>

                @if ($errors->has('url'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('url') }}
                    </p>
                @endif

            </form>

        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-7">

            <div class="card mt-4">
                <div class="card-header">
                    @lang('link.recent_links')
                </div>

                <ul class="list-group list-group-flush">
                    @forelse($recent_links as $link)
                        <a href="{{ route('links.show', [$link->id]) }}" class="list-group-item list-group-item-action">
                            {!! $link->getIcon('mr-1') !!}
                            {{ $link->title }}
                        </a>
                    @empty
                        <li class="list-group-item text-danger">
                            @lang('linkace.no_results_found', ['model' => trans('link.links')])
                        </li>
                    @endforelse
                </ul>
            </div>

        </div>
        <div class="col-12 col-md-5">

            <div class="card mt-4">
                <div class="card-header">
                    @lang('stats.stats')
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <span class="mr-1">@lang('stats.total_links')</span>
                            <span class="badge badge-secondary ml-auto">{{ $stats['total_links'] }}</span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <span class="mr-1">@lang('stats.total_categories')</span>
                            <span class="badge badge-secondary ml-auto">{{ $stats['total_categories'] }}</span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <span class="mr-1">@lang('stats.total_tags')</span>
                            <span class="badge badge-secondary ml-auto">{{ $stats['total_tags'] }}</span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <span class="mr-1">@lang('stats.total_notes')</span>
                            <span class="badge badge-secondary ml-auto">{{ $stats['total_notes'] }}</span>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>

@endsection
