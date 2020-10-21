@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('tag.edit')
        </header>
        <div class="card-body">

            <form action="{{ route('tags.update', [$tag->id]) }}" method="POST">
                @method('PATCH')
                @csrf

                <input type="hidden" name="tag_id" value="{{ $tag->id }}">

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-7">
                        <div class="form-group">
                            <label for="name">@lang('tag.name')</label>

                            <input name="name" id="name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                type="text" placeholder="@lang('placeholder.tag_name')"
                                value="{{ old('name') ?: $tag->name }}" required autofocus>

                            @if ($errors->has('name'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-5">
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

                <div class="mt-3 d-sm-flex flex-wrap align-items-center">

                    <div class="d-sm-inline-block mb-3 mb-sm-0 mr-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            onclick="window.deleteTag.submit()">
                            <x-icon.trash class="mr-2"/> @lang('tag.delete')
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <x-icon.save class="mr-2"/> @lang('tag.update')
                    </button>

                </div>

            </form>

        </div>
    </div>

    <form action="{{ route('tags.destroy', [$tag->id]) }}" method="post" id="deleteTag">
        @csrf
        @method('DELETE')
    </form>

@endsection
