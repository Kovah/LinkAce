@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('tag.add')
            </p>
        </header>
        <div class="card-content">

            <form action="{{ route('tags.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label class="label" for="name">@lang('tag.name')</label>
                    <div class="control">
                        <input name="name" id="name"
                            class="input is-large{{ $errors->has('name') ? ' is-danger' : '' }}"
                            type="text" placeholder="@lang('tag.name')" value="{{ old('name') }}"
                            required autofocus>
                    </div>
                    @if ($errors->has('name'))
                        <p class="help has-text-danger" role="alert">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>

                <br>

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

                <br>

                <div class="field">
                    <div class="control is-flex align-items-center has-text-right">

                        <label class="checkbox mr ml-auto has-text-grey-light">
                            <input type="checkbox" name="reload_view"
                                @if(session('reload_view')) checked @endif>
                            @lang('linkace.continue_adding')
                        </label>

                        <button type="submit" class="button is-primary is-medium">
                            <i class="fa fa-save fa-mr"></i> @lang('tag.add')
                        </button>

                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
