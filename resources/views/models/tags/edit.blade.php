@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('tag.add')
            </p>
        </header>
        <div class="card-content">

            <form action="{{ route('tags.update', [$tag->id]) }}" method="POST">
                @method('PATCH')
                @csrf

                <input type="hidden" name="tag_id" value="{{ $tag->id }}">

                <div class="field">
                    <label class="label" for="name">@lang('tag.name')</label>
                    <div class="control">
                        <input name="name" id="name"
                            class="input is-large{{ $errors->has('name') ? ' is-danger' : '' }}"
                            type="text" placeholder="@lang('tag.name')" value="{{ old('name') ?: $tag->name }}"
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
                                <option value="0" @if($tag->is_private === 0) selected @endif>
                                    @lang('linkace.no')
                                </option>
                                <option value="1" @if($tag->is_private === 1) selected @endif>
                                    @lang('linkace.yes')
                                </option>
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

                <div class="field has-text-right">

                    <button type="submit" class="button is-primary is-medium">
                        <i class="fa fa-save fa-mr"></i> @lang('tag.update')
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection
