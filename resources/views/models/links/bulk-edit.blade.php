@extends('layouts.app')

@section('content')
    <form action="{{ route('bulk-edit.update-links') }}" method="POST" class="card bulk-form link-form">
        @csrf
        <input type="hidden" name="models" value="{{ old('models', implode(',', $models)) }}">
        <header class="card-header">@choice('link.bulk_title', $modelCount, ['count' => $modelCount])</header>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-2 row-gap-2">
                <div>
                    <label class="form-label" for="tags">@lang('tag.update_tags')</label>
                    <input name="tags" id="tags" type="text" placeholder="@lang('placeholder.tags_select')"
                        class="tag-select"
                        data-value="{{ Link::oldTaxonomyOutputWithoutLink('tags', []) }}"
                        data-allow-creation="1" data-tag-type="tags">
                    @if ($errors->has('tags'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('tags') }}
                        </p>
                    @endif
                </div>
                <div>
                    <label class="form-label" for="tags_mode">Mode</label>
                    <select id="tags_mode" name="tags_mode" class="form-select {{ $errors->has('tags_mode') ? ' is-invalid' : '' }}">
                        <option value="append" @selected(old('tags_mode') === 'append')>
                            @lang('tag.bulk_mode_append')
                        </option>
                        <option value="replace" @selected(old('tags_mode') === 'replace')>
                            @lang('tag.bulk_mode_replace')
                        </option>
                    </select>
                </div>
            </div>

            <div class="mt-4 row row-cols-1 row-cols-md-2 row-gap-2">
                <div>
                    <label class="form-label" for="lists">@lang('list.update_lists')</label>
                    <input name="lists" id="lists" type="text" placeholder="@lang('placeholder.list_select')"
                        class="tag-select"
                        data-value="{{ Link::oldTaxonomyOutputWithoutLink('lists', []) }}"
                        data-allow-creation="1" data-tag-type="lists">
                    @if ($errors->has('lists'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('lists') }}
                        </p>
                    @endif
                </div>
                <div>
                    <label class="form-label" for="lists_mode">Mode</label>
                    <select id="lists_mode" name="lists_mode" class="form-select {{ $errors->has('lists_mode') ? ' is-invalid' : '' }}">
                        <option value="append" @selected(old('lists_mode') === 'append')>
                            @lang('list.bulk_mode_append')
                        </option>
                        <option value="replace" @selected(old('lists_mode') === 'replace')>
                            @lang('list.bulk_mode_replace')
                        </option>
                    </select>
                </div>
            </div>

            <div class="mt-4 row">
                <x-forms.visibility-toggle class="col-6" :unchanged-option="true"/>
            </div>

            <div class="mt-3 d-sm-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <x-icon.save class="me-2"/> @lang('link.update_links')
                </button>
            </div>
        </div>
    </form>

    <form action="{{ route('bulk-edit.delete') }}" method="POST" class="card mt-4">
        @csrf
        <input type="hidden" name="type" value="links">
        <input type="hidden" name="models" value="{{ implode(',', $models) }}">
        <header class="card-header">@choice('link.delete', $modelCount)</header>
        <div class="card-body">
            <div class="text-end">
                <button type="submit" class="btn btn-danger">
                    <x-icon.save class="me-2"/> @choice('link.delete', $modelCount)
                </button>
            </div>
        </div>
    </form>

@endsection
