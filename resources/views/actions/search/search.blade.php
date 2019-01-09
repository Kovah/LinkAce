@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('search.search')
        </div>
        <div class="card-body">

            <form action="{{ route('do-search') }}" method="POST">
                @csrf

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="query" id="query" required
                            class="form-control form-control-lg{{ $errors->has('query') ? ' is-invalid' : '' }}"
                            placeholder="@lang('search.query')"
                            value="{{ old('query') ?: $query_settings['old_query'] }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                @lang('search.search')
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm d-flex align-items-center small">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="search_title" name="search_title" class="custom-control-input"
                                @if($query_settings['search_title']) checked @endif>
                            <label class="custom-control-label" for="search_title">
                                @lang('search.search_title')
                            </label>
                        </div>
                    </div>

                    <div class="col-sm d-flex align-items-center small">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="search_description" name="search_description"
                                class="custom-control-input"
                                @if($query_settings['search_description']) checked @endif>
                            <label class="custom-control-label" for="search_description">
                                @lang('search.search_description')
                            </label>
                        </div>
                    </div>

                    <div class="col-sm d-flex align-items-center small">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="private_only" name="private_only" class="custom-control-input"
                                @if($query_settings['private_only']) checked @endif>
                            <label class="custom-control-label" for="private_only">
                                @lang('search.private_only')
                            </label>
                        </div>
                    </div>

                    <div class="w-100 d-xl-none mb-3"></div>

                    <div class="col-sm small mb-2 mb-sm-0">
                        <label for="only_category" class="d-none" aria-hidden="true">
                            @lang('search.filter_by_category')
                        </label>
                        <select id="only_category" name="only_category" class="custom-select custom-select-sm">
                            <option value="0">@lang('search.filter_by_category')</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    @if($query_settings['only_category'] == $category->id) selected="selected" @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm small mb-2 mb-sm-0">
                        <label for="only_tag" class="d-none" aria-hidden="true">
                            @lang('search.filter_by_tag')
                        </label>
                        <select id="only_tag" name="only_tag" class="custom-select custom-select-sm">
                            <option value="0">@lang('search.filter_by_tag')</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}"
                                    @if($query_settings['only_tag'] == $tag->id) selected @endif>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm small">
                        <label for="order_by" class="d-none" aria-hidden="true">
                            @lang('search.filter_by_tag')
                        </label>
                        <select id="order_by" name="order_by" class="custom-select custom-select-sm">
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

            </form>

        </div>
        <div class="card-table mt-3">

            @if ($errors->any())
                <div class="alert alert-danger m-3">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br/>
                    @endforeach
                </div>
            @endif

            @if($results->isEmpty())
                <div class="alert alert-info m-3">
                    @lang('search.no_results')
                </div>
            @else
                @include('actions.search._table', ['results' => $results])
            @endif

        </div>
    </div>

@endsection
