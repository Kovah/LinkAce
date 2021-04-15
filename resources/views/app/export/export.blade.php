@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('export.export')
        </div>
        <div class="card-body">

            <p>@lang('export.export_help')</p>

            <form action="{{ route('do-html-export') }}" method="post" class="mb-3">
                @csrf
                <button type="submit" name="method" value="html" class="btn btn-primary export-submit">
                    <x-icon.upload class="mr-2"/>
                    @lang('export.start_export_html')
                </button>
            </form>

            <form action="{{ route('do-csv-export') }}" method="post">
                @csrf
                <button type="submit" name="method" value="csv" class="btn btn-primary export-submit">
                    <x-icon.upload class="mr-2"/>
                    @lang('export.start_export_csv')
                </button>
            </form>

        </div>
    </div>

@endsection
