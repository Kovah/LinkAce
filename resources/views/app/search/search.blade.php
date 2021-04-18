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
                        <input type="text" name="query" id="query" autofocus
                            class="form-control form-control-lg{{ $errors->has('query') ? ' is-invalid' : '' }}"
                            placeholder="@lang('search.query')"
                            value="{{ old('query') ?: $query_settings['old_query'] }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <x-icon.search class="mr-2"/>
                                @lang('search.search')
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="search_title" name="search_title" class="custom-control-input"
                                @if($query_settings['search_title']) checked @endif>
                            <label class="custom-control-label" for="search_title">
                                @lang('search.search_title')
                            </label>
                        </div>
                    </div>

                    <div class="col-md d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="search_description" name="search_description"
                                class="custom-control-input"
                                @if($query_settings['search_description']) checked @endif>
                            <label class="custom-control-label" for="search_description">
                                @lang('search.search_description')
                            </label>
                        </div>
                    </div>

                    <div class="col-md d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="empty_tags" name="empty_tags" class="custom-control-input"
                                @if($query_settings['empty_tags']) checked @endif>
                            <label class="custom-control-label" for="empty_tags">
                                @lang('search.empty_tags')
                            </label>
                        </div>
                    </div>

                </div>
                <div class="row">

                    <div class="col-md d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="broken_only" name="broken_only" class="custom-control-input"
                                @if($query_settings['broken_only']) checked @endif>
                            <label class="custom-control-label" for="broken_only">
                                @lang('search.broken_links')
                            </label>
                        </div>
                    </div>

                    <div class="col-md d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="private_only" name="private_only" class="custom-control-input"
                                @if($query_settings['private_only']) checked @endif>
                            <label class="custom-control-label" for="private_only">
                                @lang('search.private_only')
                            </label>
                        </div>
                    </div>

                    <div class="col-md d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="empty_lists" name="empty_lists" class="custom-control-input"
                                @if($query_settings['empty_lists']) checked @endif>
                            <label class="custom-control-label" for="empty_lists">
                                @lang('search.empty_lists')
                            </label>
                        </div>
                    </div>

                </div>
                <div class="row mt-3">

                    <div class="col-md mb-2 mb-md-0">
                        <label for="only_lists" class="d-none" aria-hidden="true">
                            @lang('search.filter_by_list')
                        </label>
                        <input name="only_lists" id="only_lists" type="text" class="tags-select" data-tag-type="lists"
                            placeholder="@lang('search.filter_by_list')" value="{{ $query_settings['only_lists'] }}">
                    </div>

                    <div class="col-md mb-2 mb-md-0">
                        <label for="only_tags" class="d-none" aria-hidden="true">
                            @lang('search.filter_by_tag')
                        </label>
                        <input name="only_tags" id="only_tags" type="text" class="tags-select" data-tag-type="tags"
                            placeholder="@lang('search.filter_by_tag')" value="{{ $query_settings['only_tags'] }}">
                    </div>

                    <div class="col-md">
                        <label for="order_by" class="d-none" aria-hidden="true">
                            @lang('search.order_by')
                        </label>
                        <select id="order_by" name="order_by" class="custom-select">
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

            @if($results->isEmpty())
                <div class="alert alert-info m-3">
                    @lang('search.no_results')
                </div>
            @else
                @include('app.search.partials.table', ['results' => $results])
            @endif

        </div>
    </div>

@endsection
