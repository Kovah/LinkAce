@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('import.import_queue')
        </div>
        <div class="card-body">

            @if($jobs->isNotEmpty())
                <div class="table-responsive mb-3">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>@lang('link.url')</th>
                            <th>@lang('import.scheduled_for')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($jobs as $job)
                            @php
                                $data = unserialize(json_decode($job->payload)->data->command);
                            @endphp
                            <tr>
                                <td class="text-condensed">{{ $job->id }}</td>
                                <td class="text-condensed">{{ $data->link['url'] }}</td>
                                <td class="text-condensed">{{ \Illuminate\Support\Carbon::parse($job->available_at) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $jobs->links() }}
            @else

                <div class="alert alert-info">
                    @lang('linkace.no_results_found', ['model' => trans('link.links')])
                </div>

            @endif
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            @lang('import.failed_imports')
        </div>
        <div class="card-body">

            @if($failed_jobs->isNotEmpty())
                <div class="table-responsive mb-3">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>@lang('link.url')</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($failed_jobs as $job)
                            @php
                                $data = unserialize(json_decode($job->payload)->data->command);
                            @endphp
                            <tr>
                                <td class="text-condensed">{{ $job->id }}</td>
                                <td class="text-condensed">{{ $data->link['url'] }}</td>
                                <td class="text-condensed">{{ explode("\n", $job->exception)[0] ?? '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $failed_jobs->links() }}
            @else

                <div class="alert alert-info">
                    @lang('linkace.no_results_found', ['model' => trans('link.links')])
                </div>

            @endif
        </div>
    </div>

@endsection
