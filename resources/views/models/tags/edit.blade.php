@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('tag.add')
        </header>
        <div class="card-body">

            <form action="{{ route('tags.update', [$tag->id]) }}" method="POST">
                @method('PATCH')
                @csrf

                <input type="hidden" name="tag_id" value="{{ $tag->id }}">

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="name">@lang('tag.name')</label>

                            <input name="name" id="name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                type="text" placeholder="@lang('tag.name')" value="{{ old('name') ?: $tag->name }}"
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
                                <option value="0" @if($tag->is_private === 0) selected @endif>
                                    @lang('linkace.no')
                                </option>
                                <option value="1" @if($tag->is_private === 1) selected @endif>
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

                <div class="mt-3 text-right">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save fa-mr"></i> @lang('tag.edit')
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection
