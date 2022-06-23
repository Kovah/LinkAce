@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('list.add')
        </header>
        <div class="card-body">

            <form action="{{ route('lists.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label" for="name">@lang('list.name')</label>

                    <input name="name" id="name"
                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        type="text" placeholder="@lang('placeholder.list_name')" value="{{ old('name') }}"
                        required autofocus>

                    @if ($errors->has('name'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-7">
                        <div class="mb-4">
                            <label class="form-label" for="description">@lang('list.description')</label>

                            <textarea name="description" id="description" rows="4"
                                class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                >{{ old('description') }}</textarea>

                            @if ($errors->has('description'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-5">
                        <x-forms.visibility-toggle class="mb-4"/>
                    </div>
                </div>

                <div class="mt-3 d-sm-flex align-items-center justify-content-end">

                    <div class="form-check mb-3 mb-sm-0 me-sm-4">
                        <input class="form-check-input" type="checkbox" id="reload_view" name="reload_view"
                            @if(session('reload_view')) checked @endif>
                        <label class="form-check-label" for="reload_view">
                            @lang('linkace.continue_adding')
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <x-icon.save class="me-2"/> @lang('list.add')
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection
