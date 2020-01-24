@extends('layouts.setup')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    @lang('setup.welcome')
                </div>
                <div class="card-body">

                    <p>@lang('setup.intro')</p>

                    <ol>
                        <li>@lang('setup.intro.step1')</li>
                        <li>@lang('setup.intro.step2')</li>
                        <li>@lang('setup.intro.step3')</li>
                    </ol>

                    <a href="{{ route('setup.requirements') }}" class="btn btn-primary">
                        @lang('setup.check_requirements')
                    </a>
                </div>
            </div>

        </div>
    </div>

@endsection
