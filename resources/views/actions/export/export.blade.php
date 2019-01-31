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
                    <i class="fa"></i>
                    @lang('export.start_export')
                </button>

            </form>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $('.export-submit').click(function (e) {
            if ($('#export-form')[0].checkValidity()) {
                var $btn = $(e.currentTarget);
                $btn.prop('disabled', true);
                $btn.find('.fa').addClass('fa-fw fa-circle-notch fa-spin');
            }
        })
    </script>
@endpush
