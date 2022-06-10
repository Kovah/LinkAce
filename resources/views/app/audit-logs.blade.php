@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('audit.settings_history')
        </div>
        <div class="card-body">

            <div class="history mb-6">
                @foreach($settings_history as $entry)
                    <x-history.settings-entry :entry="$entry"/>
                @endforeach
            </div>

            {!! $settings_history->onEachSide(1)->links() !!}

        </div>
    </div>

@endsection
