<div class="card">
    <header class="card-header">
        @lang('link.add')
    </header>
    <div class="card-body">

        <form action="{{ route('links.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="label" for="url">@lang('link.url')</label>
                <input name="url" id="url" type="url"
                    class="form-control form-control-lg{{ $errors->has('url') ? ' is-invalid' : '' }}"
                    placeholder="@lang('placeholder.link_url')" value="{{ old('url') ?: $bookmark_url ?? '' }}"
                    required autofocus>

                <p class="invalid-feedback {{ $errors->has('url') ? 'd-none' : '' }}">
                    @lang('validation.unique', ['attribute' => trans('link.url')])
                </p>

                @if ($errors->has('url'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('url') }}
                    </p>
                @endif
            </div>

            <div class="row">
                <div class="col">

                    <div class="form-group">
                        <label class="label" for="title">@lang('link.title')</label>
                        <input name="title" id="title"
                            class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                            type="text" placeholder="@lang('placeholder.link_title')"
                            value="{{ old('title') ?: $bookmark_title ?? '' }}">
                        @if ($errors->has('title'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('title') }}
                            </p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description">@lang('link.description')</label>
                        <textarea name="description" id="description" rows="4" class="form-control"
                            >{{ old('description') }}</textarea>

                        @if ($errors->has('description'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('description') }}
                            </p>
                        @endif
                    </div>

                </div>
                <div class="col">

                    <div class="form-group">
                        <label for="lists">@lang('list.lists')</label>
                        <input name="lists" id="lists" type="text" placeholder="@lang('placeholder.list_select')"
                            class="tags-select" value="{{ old('lists') }}" data-tag-search="lists">

                        @if ($errors->has('lists'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('lists') }}
                            </p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="tags">@lang('tag.tags')</label>
                        <input name="tags" id="tags" type="text" placeholder="@lang('placeholder.tags_select')"
                            class="tags-select" value="{{ old('tags') }}" data-tag-search="tags">

                        @if ($errors->has('tags'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('tags') }}
                            </p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="is_private">@lang('linkace.is_private')</label>
                        <select id="is_private" name="is_private"
                            class="custom-select{{ $errors->has('is_private') ? ' is-invalid' : '' }}">
                            <option value="0">
                                @lang('linkace.no')
                            </option>
                            <option value="1" @if(usersettings('private_default') === '1') selected @endif>
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

            <div class="mt-3 d-flex align-items-center">

                @if(!isset($bookmark_url))
                    <div class="custom-control custom-checkbox ml-auto mr-4">
                        <input class="custom-control-input" type="checkbox" id="reload_view" name="reload_view"
                            @if(session('reload_view')) checked @endif>
                        <label class="custom-control-label" for="reload_view">
                            @lang('linkace.continue_adding')
                        </label>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> @lang('link.add')
                </button>

            </div>

        </form>

    </div>
</div>
