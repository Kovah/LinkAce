@extends('layouts.app')

@section('content')

    <h2>@lang('trash.trash')</h2>

    <div class="alert alert-danger">
        @lang('trash.delete_warning')
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center">
           <div>
               @lang('trash.deleted_links')
           </div>
            <div class="ml-auto">
                <a href="{{ route('clear-trash', ['links']) }}"
                    class="btn btn-sm btn-danger" title="@lang('trash.clear_trash')">
                    <i class="fa fa-recycle"></i> @lang('trash.clear_trash')
                </a>
            </div>
        </div>
        <div class="card-body">

            @include('actions.trash._link-table', ['links' => $links])

        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex align-items-center">
            <div>
                @lang('trash.deleted_categories')
            </div>
            <div class="ml-auto">
                <a href="{{ route('clear-trash', ['categories']) }}"
                    class="btn btn-sm btn-danger" title="@lang('trash.clear_trash')">
                    <i class="fa fa-recycle"></i> @lang('trash.clear_trash')
                </a>
            </div>
        </div>
        <div class="card-body">

            @include('actions.trash._category-table', ['categories' => $categories])

        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex align-items-center">
            <div>
                @lang('trash.deleted_tags')
            </div>
            <div class="ml-auto">
                <a href="{{ route('clear-trash', ['tags']) }}"
                    class="btn btn-sm btn-danger" title="@lang('trash.clear_trash')">
                    <i class="fa fa-recycle"></i> @lang('trash.clear_trash')
                </a>
            </div>
        </div>
        <div class="card-body">

            @include('actions.trash._tag-table', ['tags' => $tags])

        </div>
    </div>

@endsection
