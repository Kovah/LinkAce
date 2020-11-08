@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('link.edit')
        </header>
        <div class="card-body">

            <form action="{{ route('links.update', [$link->id]) }}" method="POST" id="link-edit">
                @method('PATCH')
                @csrf

                <input type="hidden" name="link_id" value="{{ $link->id }}">

                <div class="form-group">
                    <label class="label" for="url">@lang('link.url')</label>
                    <input name="url" id="url" type="url"
                        class="form-control form-control-lg{{ $errors->has('url') ? ' is-invalid' : '' }}"
                        placeholder="@lang('placeholder.link_url')" value="{{ old('url') ?: $link->url }}"
                        required autofocus>
                    @if ($errors->has('url'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('url') }}
                        </p>
                    @endif
                </div>

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-7">

                        <div class="form-group">
                            <label class="label" for="title">@lang('link.title')</label>
                            <input name="title" id="title"
                                class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                type="text" placeholder="@lang('placeholder.link_url')"
                                value="{{ old('title') ?: $link->title }}">
                            @if ($errors->has('title'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('title') }}
                                </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="description">@lang('link.description')</label>
                            <textarea name="description" id="description" rows="4" class="form-control"
                            >{{ old('description') ?: $link->description }}</textarea>

                            @if ($errors->has('description'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                        </div>

                    </div>
                    <div class="col-12 col-sm-6 col-md-5">

                        <div class="form-group">
                            <label for="lists">@lang('list.lists')</label>
                            <input name="lists" id="lists" type="text" placeholder="@lang('placeholder.list_select')"
                                class="tags-select" value="{{ old('lists', $link->listsForInput()) }}"
                                data-allow-creation="true" data-tag-type="lists">

                            @if ($errors->has('url'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('lists') }}
                                </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="tags">@lang('tag.tags')</label>
                            <input name="tags" id="tags" type="text" placeholder="@lang('placeholder.tags_select')"
                                class="tags-select" value="{{ old('tags', $link->tagsForInput()) }}"
                                data-allow-creation="true" data-tag-type="tags">

                            @if ($errors->has('url'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('tags') }}
                                </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="is_private">@lang('linkace.is_private')</label>
                            <select id="is_private" name="is_private"
                                class="custom-select{{ $errors->has('is_private') ? ' is-invalid' : '' }}">
                                <option value="0" @if($link->is_private === false) selected @endif>
                                    @lang('linkace.no')
                                </option>
                                <option value="1" @if($link->is_private === true) selected @endif>
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
                            onclick="window.deleteLink.submit()">
                            <x-icon.trash class="mr-2"/> @lang('link.delete')
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <x-icon.save class="mr-2"/> @lang('link.update')
                    </button>

                </div>

            </form>

        </div>
    </div>

    <form action="{{ route('links.destroy', [$link->id]) }}" method="post" id="deleteLink">
        @csrf
        @method('DELETE')
    </form>

@endsection
