@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('category.add')
        </header>
        <div class="card-body">

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">@lang('category.name')</label>

                    <input name="name" id="name"
                        class="form-control form-control-lg{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        type="text" placeholder="@lang('category.name')" value="{{ old('name') }}"
                        required autofocus>

                    @if ($errors->has('name'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>

                <br>

                <div class="row">
                    <div class="col">

                        <div class="form-group">
                            <label for="description">
                                @lang('category.description')
                            </label>

                            <textarea name="description" id="description" rows="4" class="form-control"
                                placeholder="@lang('category.description')">{{ old('description') }}</textarea>

                            @if ($errors->has('description'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                        </div>

                    </div>
                    <div class="col">

                        <div class="form-group">
                            <label for="parent_category">
                                @lang('category.parent_category')
                            </label>

                            <select id="parent_category" name="parent_category"
                                class="custom-select{{ $errors->has('parent_category') ? ' is-invalid' : '' }}">
                                <option value="0">@lang('category.select_parent_category')</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>


                            @if ($errors->has('parent_category'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('parent_category') }}
                                </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="is_private">
                                @lang('linkace.is_private')
                            </label>

                            <select id="is_private" name="is_private"
                                class="custom-select{{ $errors->has('parent_category') ? ' is-invalid' : '' }}">
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
                        <i class="fa fa-save fa-mr"></i> @lang('category.add')
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection
