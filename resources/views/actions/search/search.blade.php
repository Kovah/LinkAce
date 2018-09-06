@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <p class="card-header-title">
                @lang('search.search')
            </p>
        </div>
        <div class="card-content">

            <form action="{{ route('do-search') }}" method="POST">
                @csrf

                <div class="field">
                    <div class="field has-addons">
                        <div class="control is-expanded">
                            <input name="query" id="query" class="input{{ $errors->has('query') ? ' is-danger' : '' }}"
                                type="text" placeholder="@lang('search.query')"
                                value="{{ old('query') ?: $query_settings['old_query'] }}"
                                required>
                        </div>
                        <div class="control">
                            <button type="submit" class="button is-primary">
                                @lang('search.search')
                            </button>
                        </div>
                    </div>
                </div>

                <div class="columns">

                    <div class="column">
                        <div class="field">
                            <label class="checkbox">
                                <input type="checkbox" id="search_title" name="search_title"
                                    @if($query_settings['search_title']) checked @endif>
                                @lang('search.search_title')
                            </label>
                        </div>
                    </div>

                    <div class="column">
                        <div class="field">
                            <label class="checkbox">
                                <input type="checkbox" id="search_description" name="search_description"
                                    @if($query_settings['search_description']) checked @endif>
                                @lang('search.search_description')
                            </label>
                        </div>
                    </div>

                    <div class="column">
                        <div class="field">
                            <label class="checkbox">
                                <input type="checkbox" id="private_only" name="private_only"
                                    @if($query_settings['private_only']) checked @endif>
                                @lang('search.private_only')
                            </label>
                        </div>
                    </div>

                    <div class="column">
                        <div class="field">
                            <div class="control">
                                <div class="select is-small">
                                    <label for="only_category" class="is-hidden" aria-hidden="true">
                                        @lang('search.filter_by_category')
                                    </label>
                                    <select id="only_category" name="only_category">
                                        <option value="0">@lang('search.filter_by_category')</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if($query_settings['only_category'] == $category->id) selected="selected" @endif>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column">
                        <div class="field">
                            <div class="control">
                                <div class="select is-small">
                                    <label for="only_tag" class="is-hidden" aria-hidden="true">
                                        @lang('search.filter_by_tag')
                                    </label>
                                    <select id="only_tag" name="only_tag">
                                        <option value="0">@lang('search.filter_by_tag')</option>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}"
                                                @if($query_settings['only_tag'] == $tag->id) selected @endif>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column">
                        <div class="field">
                            <div class="control">
                                <div class="select is-small">
                                    <label for="order_by" class="is-hidden" aria-hidden="true">
                                        @lang('search.filter_by_tag')
                                    </label>
                                    <select id="order_by" name="order_by">
                                        <option value="0">@lang('search.order_by')</option>
                                        @foreach($order_by_options as $order_by)
                                            <option value="{{ $order_by }}"
                                                @if($query_settings['order_by'] == $order_by) selected @endif>
                                                @lang('search.order_by.' . $order_by)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </form>

        </div>
        <div class="card-content">

            @if ($errors->any())
                <div class="notification is-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br/>
                    @endforeach
                </div>
            @endif

            @if($results->isEmpty())
                <div class="notification is-warning">
                    @lang('search.no_results')
                </div>
            @else
                @include('actions.search._table', ['results' => $results])
            @endif

        </div>
    </div>

@endsection
