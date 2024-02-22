<div class="mb-3 my-5">
    <h5 class="mb-3">@lang('settings.privacy')</h5>
    <p class="my-3 small">@lang('settings.default_visibility_help')</p>

    <div class="row">
        <div class="col-12 col-sm-8 col-md-6">

            <div class="mb-4">
                <label class="form-label" for="links_default_visibility">
                    @lang('settings.links_default_visibility')
                </label>
                <select id="links_default_visibility" name="links_default_visibility"
                    class="form-select{{ $errors->has('links_default_visibility') ? ' is-invalid' : '' }}">
                    <x-forms.visibility-options :setting="usersettings('links_default_visibility')"/>
                </select>
                @if ($errors->has('links_default_visibility'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('links_default_visibility') }}
                    </p>
                @endif
            </div>

        </div>
        <div class="col-12 col-sm-8 col-md-6">

            <div class="mb-4">
                <label class="form-label" for="notes_default_visibility">
                    @lang('settings.notes_default_visibility')
                </label>
                <select id="notes_default_visibility" name="notes_default_visibility"
                    class="form-select{{ $errors->has('notes_default_visibility') ? ' is-invalid' : '' }}">
                    <x-forms.visibility-options :setting="usersettings('notes_default_visibility')"/>
                </select>
                @if ($errors->has('notes_default_visibility'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('notes_default_visibility') }}
                    </p>
                @endif
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-8 col-md-6">

            <div class="mb-4">
                <label class="form-label" for="lists_default_visibility">
                    @lang('settings.lists_default_visibility')
                </label>
                <select id="lists_default_visibility" name="lists_default_visibility"
                    class="form-select{{ $errors->has('lists_default_visibility') ? ' is-invalid' : '' }}">
                    <x-forms.visibility-options :setting="usersettings('lists_default_visibility')"/>
                </select>
                @if ($errors->has('lists_default_visibility'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('lists_default_visibility') }}
                    </p>
                @endif
            </div>

        </div>
        <div class="col-12 col-sm-8 col-md-6">

            <div class="mb-4">
                <label class="form-label" for="tags_default_visibility">
                    @lang('settings.tags_default_visibility')
                </label>
                <select id="tags_default_visibility" name="tags_default_visibility"
                    class="form-select{{ $errors->has('tags_default_visibility') ? ' is-invalid' : '' }}">
                    <x-forms.visibility-options :setting="usersettings('tags_default_visibility')"/>
                </select>
                @if ($errors->has('tags_default_visibility'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('tags_default_visibility') }}
                    </p>
                @endif
            </div>

        </div>
    </div>

</div>
