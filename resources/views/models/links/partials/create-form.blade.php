<div class="card">
    <header class="card-header">
        @lang('link.add')
    </header>
    <div class="card-body">

        <form action="{{ route('links.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="form-label" for="url">@lang('link.url')</label>
                <input name="url" id="url" type="url"
                    class="form-control form-control-lg{{ $errors->has('url') || $existing_link ? ' is-invalid' : '' }}"
                    placeholder="@lang('placeholder.link_url')" value="{{ old('url') ?: $bookmark_url ?? '' }}"
                    required autofocus>

                <p class="invalid-feedback link-exists {{ $existing_link ? '' : 'd-none' }}">
                    @lang('link.existing_found')
                    <a href="{{ route('links.edit', [$existing_link->id ?? 0]) }}">@lang('link.edit')</a>
                </p>

                @error('url')
                <p class="invalid-feedback" role="alert">
                    {{ $errors->first('url') }}
                </p>
                @enderror
            </div>

            <div class="row">
                <div class="col-12 col-sm-6 col-md-7">

                    <div class="mb-4">
                        <label class="form-label" for="title">@lang('link.title')</label>
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

                    <div class="mb-4">
                        <label class="form-label" for="description">@lang('link.description')</label>
                        <textarea name="description" id="description" rows="4" class="form-control"
                        >{{ old('description') ?: $bookmark_description ?? '' }}</textarea>

                        @if ($errors->has('description'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('description') }}
                            </p>
                        @endif
                    </div>

                </div>
                <div class="col-12 col-sm-6 col-md-5">

                    <div class="mb-4">
                        <label class="form-label" for="lists">@lang('list.lists')</label>
                        <input name="lists" id="lists" type="text" placeholder="@lang('placeholder.list_select')"
                            class="tag-select" data-tag-data="{{ $all_lists->toJson() }}"
                            data-value="{{ Link::oldTaxonomyOutputWithoutLink('lists', $bookmark_lists ?? []) }}"
                            data-allow-creation="1" data-tag-type="lists">

                        @if ($errors->has('lists'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('lists') }}
                            </p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="tags">@lang('tag.tags')</label>
                        <input name="tags" id="tags" type="text" placeholder="@lang('placeholder.tags_select')"
                            class="tag-select" data-tag-data="{{ $all_tags->toJson() }}"
                            data-value="{{ Link::oldTaxonomyOutputWithoutLink('tags', $bookmark_tags ?? []) }}"
                            data-allow-creation="1" data-tag-type="tags">

                        @if ($errors->has('tags'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('tags') }}
                            </p>
                        @endif

                        <div class="tag-suggestions mt-2 d-none">
                            <small>@lang('We found some tags which might be interesting...')</small>
                            <div class="tag-suggestions-content mt-1"></div>
                        </div>
                    </div>

                    <x-forms.visibility-toggle class="mb-4"/>

                </div>
            </div>

            <div class="mt-3 d-sm-flex align-items-center justify-content-end">

                @if(!isset($bookmark_url))
                    <div class="form-check mb-3 mb-sm-0 me-sm-4">
                        <input class="form-check-input" type="checkbox" id="reload_view" name="reload_view"
                            @if(session('reload_view')) checked @endif>
                        <label class="form-check-label" for="reload_view">
                            @lang('linkace.continue_adding')
                        </label>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">
                    <x-icon.save class="me-2"/> @lang('link.add')
                </button>

            </div>

        </form>

    </div>
</div>
