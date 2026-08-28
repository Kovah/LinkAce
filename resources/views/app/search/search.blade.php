<?php

use App\Enums\ModelAttribute;

?>
@extends('layouts.app')

@section('content')

    <div class="search card">
        <div class="card-header">
            @lang('search.search')
        </div>
        <div class="card-body">

            <form action="{{ route('get-search') }}" method="GET" class="search-form">

                <div class="mb-4">
                    <label for="query" class="visually-hidden">@lang('search.query')</label>
                    <div class="input-group">
                        <input type="text" name="query" id="query" autofocus
                            class="form-control form-control-lg{{ $errors->has('query') ? ' is-invalid' : '' }}"
                            placeholder="@lang('search.query')"
                            value="{{ old('query') ?: $query_settings['old_query'] }}">
                        <button class="btn btn-primary" type="submit">
                            <x-icon.search class="me-2"/>
                            @lang('search.search')
                        </button>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md d-flex align-items-center">
                        <div class="form-check">
                            <input type="checkbox" id="search_title" name="search_title" class="form-check-input"
                                @if($query_settings['search_title']) checked @endif>
                            <label class="form-check-label" for="search_title">
                                @lang('search.search_title')
                            </label>
                        </div>
                    </div>

                    <div class="col-md d-flex align-items-center">
                        <div class="form-check">
                            <input type="checkbox" id="search_description" name="search_description"
                                class="form-check-input"
                                @if($query_settings['search_description']) checked @endif>
                            <label class="form-check-label" for="search_description">
                                @lang('search.search_description')
                            </label>
                        </div>
                    </div>

                    <div class="col-md d-flex align-items-center">
                        <div class="form-check">
                            <input type="checkbox" id="broken_only" name="broken_only" class="form-check-input"
                                @if($query_settings['broken_only']) checked @endif>
                            <label class="form-check-label" for="broken_only">
                                @lang('search.broken_links')
                            </label>
                        </div>
                    </div>

                </div>
                <div class="row">

                    <div class="col-md d-flex align-items-center">
                        <div class="form-check">
                            <input type="checkbox" id="empty_tags" name="empty_tags" class="form-check-input"
                                @if($query_settings['empty_tags']) checked @endif>
                            <label class="form-check-label" for="empty_tags">
                                @lang('search.empty_tags')
                            </label>
                        </div>
                    </div>

                    <div class="col-md d-flex align-items-center">
                        <div class="form-check">
                            <input type="checkbox" id="empty_lists" name="empty_lists" class="form-check-input"
                                @if($query_settings['empty_lists']) checked @endif>
                            <label class="form-check-label" for="empty_lists">
                                @lang('search.empty_lists')
                            </label>
                        </div>
                    </div>

                    <div class="col-md d-flex align-items-center">
                        <label for="visibility" class="visually-hidden">@lang('linkace.visibility')</label>
                        <select id="visibility" name="visibility"
                            class="form-select form-select-sm {{ $errors->has('visibility') ? ' is-invalid' : '' }}">
                            <option value>@lang('search.visibility')</option>
                            <option value="{{ ModelAttribute::VISIBILITY_PUBLIC }}"
                                {{ (int)old('visibility', $query_settings['visibility']) === ModelAttribute::VISIBILITY_PUBLIC
                                    ? 'selected' : '' }}>
                                @lang('linkace.visibility')
                                : @lang('attributes.visibility.' . ModelAttribute::VISIBILITY_PUBLIC)
                            </option>
                            <option value="{{ ModelAttribute::VISIBILITY_INTERNAL }}"
                                {{ (int)old('visibility', $query_settings['visibility']) === ModelAttribute::VISIBILITY_INTERNAL
                                    ? 'selected' : '' }}>
                                @lang('linkace.visibility')
                                : @lang('attributes.visibility.' . ModelAttribute::VISIBILITY_INTERNAL)
                            </option>
                            <option value="{{ ModelAttribute::VISIBILITY_PRIVATE }}"
                                {{ (int)old('visibility', $query_settings['visibility']) === ModelAttribute::VISIBILITY_PRIVATE
                                    ? 'selected' : '' }}>
                                @lang('linkace.visibility')
                                : @lang('attributes.visibility.' . ModelAttribute::VISIBILITY_PRIVATE)
                            </option>
                        </select>
                    </div>

                </div>
                <div class="row mt-4">
                    <input type="hidden" name="list_mode" id="list_mode"
                        value="{{ old('list_mode', $query_settings['list_mode']) }}">
                    <input type="hidden" name="tag_mode" id="tag_mode"
                        value="{{ old('tag_mode', $query_settings['tag_mode']) }}">

                    <div class="col-md mb-3 mb-md-0">
                        <label for="only_lists" class="d-none" aria-hidden="true">
                            @lang('search.filter_by_list')
                        </label>
                        <input name="only_lists" id="only_lists" type="text"
                            placeholder="@lang('search.filter_by_list')"
                            class="tag-select" data-tag-data="{{ $all_lists->toJson() }}"
                            data-value="{{ json_encode($query_settings['only_lists']) }}"
                            data-tag-type="lists" data-filter-role="include">
                        <div
                            class="search-filter-exclusion mt-2 {{ $query_settings['exclude_lists'] === [] ? 'd-none' : '' }}"
                            data-filter-exclude-wrapper="lists">
                            <label for="exclude_lists" class="d-none" aria-hidden="true">
                                @lang('search.exclude_lists')
                            </label>
                            <input name="exclude_lists" id="exclude_lists" type="text"
                                placeholder="@lang('search.exclude_lists')"
                                class="tag-select" data-tag-data="{{ $all_lists->toJson() }}"
                                data-value="{{ json_encode($query_settings['exclude_lists']) }}"
                                data-tag-type="lists" data-filter-role="exclude">
                            <button type="button" class="btn btn-link btn-xs p-0 mt-1"
                                data-filter-remove-exclusion="lists">
                                @lang('search.remove_exclusion')
                            </button>
                        </div>
                    </div>

                    <div class="col-md mb-3 mb-md-0">
                        <label for="only_tags" class="d-none" aria-hidden="true">
                            @lang('search.filter_by_tag')
                        </label>
                        <input name="only_tags" id="only_tags" type="text" placeholder="@lang('search.filter_by_tag')"
                            class="tag-select" data-tag-data="{{ $all_tags->toJson() }}"
                            data-value="{{ json_encode($query_settings['only_tags']) }}"
                            data-tag-type="tags" data-filter-role="include">
                        <div
                            class="search-filter-exclusion mt-2 {{ $query_settings['exclude_tags'] === [] ? 'd-none' : '' }}"
                            data-filter-exclude-wrapper="tags">
                            <label for="exclude_tags" class="d-none" aria-hidden="true">
                                @lang('search.exclude_tags')
                            </label>
                            <input name="exclude_tags" id="exclude_tags" type="text"
                                placeholder="@lang('search.exclude_tags')"
                                class="tag-select" data-tag-data="{{ $all_tags->toJson() }}"
                                data-value="{{ json_encode($query_settings['exclude_tags']) }}"
                                data-tag-type="tags" data-filter-role="exclude">
                            <button type="button" class="btn btn-link btn-xs p-0 mt-1"
                                data-filter-remove-exclusion="tags">
                                @lang('search.remove_exclusion')
                            </button>
                        </div>
                    </div>

                    <div class="col-md">
                        <label for="order_by" class="d-none" aria-hidden="true">
                            @lang('search.order_by')
                        </label>
                        <select id="order_by" name="order_by" class="form-select">
                            <option value="0">@lang('search.order_by')</option>
                            @foreach($order_by_options as $order_by)
                                <option value="{{ $order_by }}"
                                    @if($query_settings['order_by'] === $order_by) selected @endif>
                                    @lang('search.order_by.' . $order_by)
                                </option>
                            @endforeach
                            <option value="random"
                                @if($query_settings['order_by'] === 'random') selected @endif>
                                @lang('search.order_by.random')
                            </option>
                        </select>
                    </div>

                </div>

            </form>

        </div>
        <div class="card-table mt-4">

            @if($results->isEmpty())
                @if($query_settings['performed_search'])
                    <div class="alert alert-info m-3">
                        @lang('search.no_results')
                    </div>
                @endif
            @else
                @include('app.search.partials.table', ['results' => $results])
            @endif

        </div>
    </div>

    @if($results->isNotEmpty())
        <div class="mt-4">
            {!! $results->onEachSide(1)->withQueryString()->links() !!}
        </div>
    @endif

@endsection
