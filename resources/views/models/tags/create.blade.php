@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('tag.add')
        </header>
        <div class="card-body">

            <form action="{{ route('tags.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="name">@lang('tag.name')</label>

                            <input name="name" id="name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                type="text" placeholder="@lang('tag.name')" value="{{ old('name') }}"
                                required autofocus>

                            @if ($errors->has('name'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="is_private">@lang('linkace.is_private')</label>

                            <select id="is_private" name="is_private"
                                class="custom-select{{ $errors->has('is_private') ? ' is-invalid' : '' }}">
                                <option value="0">@lang('linkace.no')</option>
                                <option value="1">@lang('linkace.yes')</option>
                            </select>

                            @if ($errors->has('is_private'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('is_private') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex align-items-center">

                    <div class="custom-control custom-checkbox ml-auto mr-4">
                        <input class="custom-control-input" type="checkbox" id="reload_view" name="reload_view"
                            @if(session('reload_view')) checked @endif>
                        <label class="custom-control-label" for="reload_view">
                            @lang('linkace.continue_adding')
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save fa-mr"></i> @lang('tag.add')
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection
