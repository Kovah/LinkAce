@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('import.import')
        </div>
        <div class="card-body">

            <form id="import-form" action="{{ route('do-import') }}" method="post" enctype="multipart/form-data">
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

                <button type="submit" class="btn btn-primary import-submit">
                    <i class="fas fa-file-import mr-2"></i>
                    @lang('import.start_import')
                </button>

            </form>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $('.import-submit').click(function (e) {
            if ($('#import-form')[0].checkValidity()) {
                var $btn = $(e.currentTarget);
                $btn.prop('disabled', true);
                $btn.find('.fa').addClass('fa-fw fa-circle-notch fa-spin');
            }
        })
    </script>
@endpush
