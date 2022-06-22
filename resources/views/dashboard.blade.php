@extends('layouts.app')

@section('content')

    <h3 class="mb-4">@lang('user.hello', ['user' => auth()->user()->name])</h3>

    <div class="row">
        <div class="col-12 col-md-7">
            <form action="{{ route('links.store') }}" method="POST">
                @csrf

                <input type="hidden" name="is_private" value="{{ usersettings('links_private_default') ?: 0 }}">
                <input type="hidden" name="title" value="">
                <input type="hidden" name="description" value="">
                <input type="hidden" name="lists" value="">
                <input type="hidden" name="tags" value="">

                <div class="input-group">
                    <input type="url" id="url" name="url" required
                        class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                        placeholder="@lang('link.url')" value="{{ old('url') }}"
                        aria-label="@lang('link.url')">
                    <button class="btn btn-primary" type="submit">
                        <x-icon.plus class="me-2"/> @lang('link.add_quick')
                    </button>
                </div>

                @if ($errors->has('url'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('url') }}
                    </p>
                @endif
            </form>
        </div>
        <div class="col-12 col-md-5 mt-4 mt-md-0">
            <form action="{{ route('do-search') }}" method="POST">
                @csrf
                <input type="hidden" name="search_title" value="on">
                <input type="hidden" name="search_description" value="on">

                <label for="query" class="visually-hidden">@lang('search.query')</label>
                <div class="input-group">
                    <input type="text" name="query" id="query" required minlength="1"
                        class="form-control" placeholder="@lang('search.query')">
                    <button class="btn btn-primary" type="submit">
                        <x-icon.search class="me-2"/>
                        @lang('search.search')
                    </button>
                </div>
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
                        <a href="{{ route('links.show', [$link->id]) }}"
                            class="list-group-item list-group-item-action one-line">
                            {!! $link->getIcon('me-1') !!}
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
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="me-1">@lang('stats.total_links')</span>
                        <span class="badge bg-secondary">{{ $stats['total_links'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="me-1">@lang('stats.total_lists')</span>
                        <span class="badge bg-secondary">{{ $stats['total_lists'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="me-1">@lang('stats.total_tags')</span>
                        <span class="badge bg-secondary">{{ $stats['total_tags'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="me-1">@lang('stats.total_notes')</span>
                        <span class="badge bg-secondary">{{ $stats['total_notes'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="me-1">@lang('stats.total_broken_links')</span>
                        <form action="{{ route('do-search') }}" method="post" class="d-inline-block">
                            @csrf
                            <input type="hidden" name="broken_only" value="on">
                            <button type="submit"
                                class="badge border-0 {{ $stats['total_broken_links'] > 0 ? 'bg-danger' : 'bg-secondary' }}">
                                {{ $stats['total_broken_links'] }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-7">

            <div class="card mt-4">
                <div class="card-header">
                    @lang('list.recent_lists')
                </div>

                <div class="card-body">
                    @forelse($recent_lists as $list)
                        <a href="{{ route('lists.show', [$list->id]) }}" class="btn btn-light btn-sm">
                            {{ $list->name }}
                        </a>
                    @empty
                        <div class="text-danger">
                            @lang('linkace.no_results_found', ['model' => trans('list.lists')])
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
        <div class="col-12 col-md-5">

            <div class="card mt-4">
                <div class="card-header">
                    @lang('tag.recent_tags')
                </div>

                <div class="card-body">
                    @forelse($recent_tags as $tag)
                        <a href="{{ route('tags.show', [$tag->id]) }}" class="btn btn-light btn-sm">
                            {{ $tag->name }}
                        </a>
                    @empty
                        <div class="text-danger">
                            @lang('linkace.no_results_found', ['model' => trans('tag.tags')])
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

@endsection
