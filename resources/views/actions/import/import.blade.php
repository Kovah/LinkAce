@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('import.import')
        </div>
        <div class="card-body">

            <form class="import-form" data-action="{{ route('do-import') }}" data-csrf="{{ csrf_token() }}">

                <p>@lang('import.import_help')</p>

                <div class="form-group mt-4 mb-4">
                    <label for="username">
                        @lang('import.import_file')
                    </label>
                    <input type="file" name="import-file" id="import-file" required
                        class="form-control-file{{ $errors->has('import-file') ? ' is-invalid' : '' }}">
                    @if ($errors->has('import-file'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('import-file') }}
                        </p>
                    @endif
                </div>

                <div class="import-alerts">
                    <div class="import-alert-networkerror alert alert-danger d-none">@lang('import.import_error')</div>
                    <div class="import-alert-warning alert alert-warning d-none"></div>
                    <div class="import-alert-success alert alert-success d-none"></div>
                </div>

                <button type="button" class="btn btn-primary import-submit">
                    <span class="import-submit-processing d-none">
                        <i class="fas fa-cog fa-spin mr-2"></i>
                        @lang('import.import_running')
                    </span>
                    <span class="import-submit-default">
                        <i class="fas fa-file-import mr-2"></i>
                        @lang('import.start_import')
                    </span>
                </button>

            </form>

        </div>
    </div>

@endsection
