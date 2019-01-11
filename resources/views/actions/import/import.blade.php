@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('import.import')
        </div>
        <div class="card-body">

            <form action="{{ route('do-import') }}" method="post" enctype="multipart/form-data">
                @csrf

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

                <button type="submit" class="btn btn-primary">
                    @lang('import.start_import')
                </button>

            </form>

        </div>
    </div>

@endsection
