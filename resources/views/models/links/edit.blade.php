@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('link.edit')
            </p>
        </header>
        <div class="card-content">

            <form action="{{ route('links.update', [$link->id]) }}" method="POST">
                @method('PATCH')
                @csrf

                <input type="hidden" name="link_id" value="{{ $link->id }}">

                <div class="field">
                    <label class="label" for="url">@lang('link.url')</label>
                    <div class="control">
                        <input name="url" id="url" class="input is-large{{ $errors->has('url') ? ' is-danger' : '' }}"
                            type="url" placeholder="@lang('link.url')" value="{{ old('url') ?: $link->url }}"
                            required autofocus>
                    </div>
                    @if ($errors->has('url'))
                        <p class="help has-text-danger" role="alert">
                            {{ $errors->first('url') }}
                        </p>
                    @endif
                </div>

                <br>

                <div class="columns">
                    <div class="column is-half">

                        <div class="field">
                            <label class="label" for="title">@lang('link.title')</label>
                            <div class="control">
                                <input name="title" id="title"
                                    class="input{{ $errors->has('title') ? ' is-danger' : '' }}"
                                    type="text" placeholder="@lang('link.title')"
                                    value="{{ old('title') ?: $link->title }}">
                            </div>
                            @if ($errors->has('title'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('title') }}
                                </p>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label" for="description">@lang('link.description')</label>
                            <div class="control">
                                <textarea name="description" id="description" rows="4" class="textarea"
                                    placeholder="@lang('link.description')"
                                >{{ old('description') ?: $link->description }}</textarea>
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
                            <label class="label" for="category_id">@lang('category.category')</label>
                            <div class="control">
                                <div class="select{{ $errors->has('category_id') ? ' is-danger' : '' }}">
                                    <select id="category_id" name="category_id">
                                        <option value="0">@lang('category.select_category')</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if($link->category_id === $category->id) selected @endif>
                                                {{ $category->name }}
                                            </option>
                                            @if($category->childCategories)
                                                @foreach($category->childCategories as $child_category)
                                                    <option value="{{ $child_category->id }}">
                                                        &rightarrow; {{ $child_category->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('category_id'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('category_id') }}
                                </p>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label" for="tags">@lang('tag.tags')</label>
                            <div class="control">
                                <input name="tags" id="tags" type="text" placeholder="@lang('tag.tags')"
                                    value="{{ old('tags') ?: $link->tagsForInput() }}">
                            </div>
                            @if ($errors->has('url'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('tags') }}
                                </p>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label" for="is_private">@lang('linkace.is_private')</label>
                            <div class="control">
                                <div class="select{{ $errors->has('is_private') ? ' is-danger' : '' }}">
                                    <select id="category" name="is_private">
                                        <option value="0" @if($link->is_private === 0) selected @endif>
                                            @lang('linkace.no')
                                        </option>
                                        <option value="1" @if($link->is_private === 1) selected @endif>
                                            @lang('linkace.yes')
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('is_private'))
                                <p class="help has-text-danger" role="alert">
                                    {{ $errors->first('is_private') }}
                                </p>s
                            @endif
                        </div>

                    </div>
                </div>

                <br>

                <div class="field">
                    <div class="control has-text-right">
                        <button type="submit" class="button is-primary is-medium">
                            <i class="fa fa-save fa-mr"></i> @lang('link.update')
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
        @include('models.links._tags-js')
    @endpush

@endsection
