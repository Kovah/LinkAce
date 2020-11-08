@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('list.add')
        </header>
        <div class="card-body">

            <form action="{{ route('lists.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">@lang('list.name')</label>

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
                        <div class="form-group">
                            <label for="description">@lang('list.description')</label>

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
                        <div class="form-group">
                            <label for="is_private">@lang('linkace.is_private')</label>

                            <select id="is_private" name="is_private"
                                class="custom-select{{ $errors->has('is_private') ? ' is-invalid' : '' }}">
                                <option value="0">@lang('linkace.no')</option>
                                <option value="1" @if(usersettings('lists_private_default') === '1') selected @endif>
                                    @lang('linkace.yes')
                                </option>
                            </select>

                            @if ($errors->has('is_private'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('is_private') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-sm-flex align-items-center justify-content-end">

                    <div class="custom-control custom-checkbox mb-3 mb-sm-0 mr-sm-4">
                        <input class="custom-control-input" type="checkbox" id="reload_view" name="reload_view"
                            @if(session('reload_view')) checked @endif>
                        <label class="custom-control-label" for="reload_view">
                            @lang('linkace.continue_adding')
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <x-icon.save class="mr-2"/> @lang('list.add')
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection
