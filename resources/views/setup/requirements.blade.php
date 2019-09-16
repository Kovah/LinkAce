@extends('layouts.setup')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    @lang('setup.setup')
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('setup.requirements') }}">
                        @csrf

                        <button type="submit" class="btn btn-primary btn-block">
                            @lang('setup.setup')
                        </button>

                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
