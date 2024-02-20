@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            @lang('audit.system_events')
        </div>
        <div class="card-body">

            <div class="history mb-4">
                @forelse($activities as $activity)
                    <x-history.activity-entry :activity="$activity"/>
                @empty
                    <div class="text-muted">@lang('audit.no_logs_found')</div>
                @endforelse
            </div>

            {!! $activities->onEachSide(1)->links() !!}

        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            @lang('audit.settings_history')
        </div>
        <div class="card-body">

            <div class="history mb-4">
                @forelse($settings_history as $entry)
                    <x-history.settings-entry :entry="$entry"/>
                @empty
                    <div class="text-muted">@lang('audit.no_logs_found')</div>
                @endforelse
            </div>

            {!! $settings_history->onEachSide(1)->links() !!}

        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            @lang('audit.user_history')
        </div>
        <div class="card-body">

            <div class="history mb-4">
                @forelse($user_history as $entry)
                    <x-history.user-entry :entry="$entry"/>
                @empty
                    <div class="text-muted">@lang('audit.no_logs_found')</div>
                @endforelse
            </div>

            {!! $user_history->onEachSide(1)->links() !!}

        </div>
    </div>

@endsection
