@extends('layouts.app')

@section('content')

    <h3 class="mb-4">@lang('user.hello', ['user' => auth()->user()->name])</h3>

    <div class="row">
        <div class="col-12 col-md-7">

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
                        <input type="hidden" name="lists" value="">
                        <input type="hidden" name="tags" value="">

                        <div class="input-group">
                            <input type="url" id="url" name="url" required
                                class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                placeholder="@lang('link.url')" value="{{ old('url') }}"
                                aria-label="@lang('link.url')">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <x-icon.plus class="mr-2"/> @lang('linkace.add')
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

        </div>
        <div class="col-12 col-md-5">

            <div class="card">
                <div class="card-header">
                    @lang('search.search')
                </div>
                <div class="card-body">

                    <form action="{{ route('do-search') }}" method="POST">
                        @csrf
                        <input type="hidden" name="search_title" value="on">
                        <input type="hidden" name="search_description" value="on">

                        <div class="input-group">
                            <input type="text" name="query" id="query" autofocus
                                class="form-control" placeholder="@lang('search.query')">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <x-icon.search class="mr-2"/>
                                    @lang('search.search')
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

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
                            <span class="mr-1">@lang('stats.total_lists')</span>
                            <span class="badge badge-secondary ml-auto">{{ $stats['total_lists'] }}</span>
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
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <span class="mr-1">@lang('stats.total_broken_links')</span>
                            <form action="{{ route('do-search') }}" method="post" class="d-inline-block ml-auto">
                                @csrf
                                <input type="hidden" name="broken_only" value="on">
                                <button type="submit"
                                    class="badge border-0 {{ $stats['total_broken_links'] > 0 ? 'badge-danger' : 'badge-secondary' }}">
                                    {{ $stats['total_broken_links'] }}
                                </button>
                            </form>
                        </div>
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
                        <a href="{{ route('lists.show', [$list->id]) }}" class="badge badge-light badge-lg">
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
                        <a href="{{ route('tags.show', [$tag->id]) }}" class="badge badge-light badge-lg">
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
