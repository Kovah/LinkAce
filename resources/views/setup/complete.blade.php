@extends('layouts.setup')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    @lang('setup.completed')
                </div>
                <div class="card-body">

                    <p>@lang('setup.outro')</p>

                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        @lang('linkace.go_to_dashboard')
                    </a>
                </div>
            </div>

        </div>
    </div>

@endsection
