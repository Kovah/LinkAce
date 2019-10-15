@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('export.export')
        </div>
        <div class="card-body">

            <form id="export-form" action="{{ route('do-export') }}" method="post">
                @csrf

                <p>@lang('export.export_help')</p>

                <button type="submit" class="btn btn-primary export-submit">
                    <i class="fas fa-upload mr-2"></i>
                    @lang('export.start_export')
                </button>

            </form>

        </div>
    </div>

@endsection
