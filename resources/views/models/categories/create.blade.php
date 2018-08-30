@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('category.add')
            </p>
        </header>
        <div class="card-content">

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label class="label" for="name">@lang('category.name')</label>
                    <div class="control">
                        <input name="name" id="name" class="input is-large{{ $errors->has('name') ? ' is-danger' : '' }}"
                            type="text" placeholder="@lang('category.name')" value="{{ old('name') }}"
                            required autofocus>
                    </div>
                    @if ($errors->has('name'))
                        <p class="help has-text-danger" role="alert">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>

                <br>

                <div class="columns">
                    <div class="column is-half">

                        <div class="field">
                            <label class="label" for="description">@lang('category.description')</label>
                            <div class="control">
                                <textarea name="description" id="description" rows="4" class="textarea"
                                    placeholder="@lang('category.description')">{{ old('description') }}</textarea>
                            </div>
                            @if ($errors->has('description'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                        </div>

                    </div>
                    <div class="column is-half">

                        <div class="field">
                            <label class="label" for="parent_category">@lang('category.parent_category')</label>
                            <div class="control">
                                <div class="select{{ $errors->has('parent_category') ? ' is-danger' : '' }}">
                                    <select id="parent_category" name="parent_category">
                                        <option value="0">@lang('category.select_parent_category')</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('parent_category'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('parent_category') }}
                                </p>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label" for="is_private">@lang('linkace.is_private')</label>
                            <div class="control">
                                <div class="select{{ $errors->has('is_private') ? ' is-danger' : '' }}">
                                    <select id="is_private" name="is_private">
                                        <option value="0">@lang('linkace.no')</option>
                                        <option value="1">@lang('linkace.yes')</option>
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('is_private'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('is_private') }}
                                </p>
                            @endif
                        </div>

                    </div>
                </div>

                <br>

                <div class="field">
                    <div class="control is-flex align-items-center has-text-right">

                        <label class="checkbox mr ml-auto has-text-grey-light">
                            <input type="checkbox" name="reload_view"
                                @if(session('reload_view')) checked @endif>
                            @lang('linkace.continue_adding')
                        </label>

                        <button type="submit" class="button is-primary is-medium">
                            <i class="fa fa-save fa-mr"></i> @lang('category.add')
                        </button>

                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
