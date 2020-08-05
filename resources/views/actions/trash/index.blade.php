@extends('layouts.app')

@section('content')

    <h2>@lang('trash.trash')</h2>

    <div class="alert alert-danger my-4">
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
                    <i class="fas fa-recycle"></i> @lang('trash.clear_trash')
                </a>
            </div>
        </div>
        <div class="card-body">

            @includeWhen($links->isNotempty(), 'actions.trash.partials.link-table', ['links' => $links])
            @if($links->isEmpty())
                <small class="text-muted">@lang('trash.delete_no_entries')</small>
            @endif

        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header d-flex align-items-center">
            <div>
                @lang('trash.deleted_lists')
            </div>
            <div class="ml-auto">
                <a href="{{ route('clear-trash', ['lists']) }}"
                    class="btn btn-sm btn-danger" title="@lang('trash.clear_trash')">
                    <i class="fas fa-recycle"></i> @lang('trash.clear_trash')
                </a>
            </div>
        </div>
        <div class="card-body">

            @includeWhen($lists->isNotEmpty(), 'actions.trash.partials.list-table', ['lists' => $lists])
            @if($lists->isEmpty())
                <small class="text-muted">@lang('trash.delete_no_entries')</small>
            @endif

        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header d-flex align-items-center">
            <div>
                @lang('trash.deleted_tags')
            </div>
            <div class="ml-auto">
                <a href="{{ route('clear-trash', ['tags']) }}"
                    class="btn btn-sm btn-danger" title="@lang('trash.clear_trash')">
                    <i class="fas fa-recycle"></i> @lang('trash.clear_trash')
                </a>
            </div>
        </div>
        <div class="card-body">

            @includeWhen($tags->isNotEmpty(), 'actions.trash.partials.tag-table', ['tags' => $tags])
            @if($tags->isEmpty())
                <small class="text-muted">@lang('trash.delete_no_entries')</small>
            @endif

        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header d-flex align-items-center">
            <div>
                @lang('trash.deleted_notes')
            </div>
            <div class="ml-auto">
                <a href="{{ route('clear-trash', ['notes']) }}"
                    class="btn btn-sm btn-danger" title="@lang('trash.clear_trash')">
                    <i class="fas fa-recycle"></i> @lang('trash.clear_trash')
                </a>
            </div>
        </div>
        <div class="card-body">

            @includeWhen($notes->isNotEmpty(), 'actions.trash.partials.note-table', ['notes' => $notes])

            @if($notes->isEmpty())
                <small class="text-muted">@lang('trash.delete_no_entries')</small>
            @endif

        </div>
    </div>

@endsection
