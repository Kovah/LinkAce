<div class="mb-3 my-5">
    <h5 class="mb-3">
        @lang('settings.privacy')
    </h5>

    <div class="row">
        <div class="col-12 col-sm-8 col-md-6">

            <div class="mb-4">
                <label class="form-label" for="links_private_default">
                    @lang('settings.links_private_default')
                </label>
                <select id="links_private_default" name="links_private_default"
                    class="form-select{{ $errors->has('links_private_default') ? ' is-invalid' : '' }}">
                    <option value="0"
                        @if(usersettings('links_private_default') === false) selected @endif>
                        @lang('linkace.no')
                    </option>
                    <option value="1"
                        @if(usersettings('links_private_default') === true) selected @endif>
                        @lang('linkace.yes')
                    </option>
                </select>
                <p class="text-muted small mt-1">@lang('settings.links_private_default_help')</p>
                @if ($errors->has('links_private_default'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('links_private_default') }}
                    </p>
                @endif
            </div>

        </div>
        <div class="col-12 col-sm-8 col-md-6">

            <div class="mb-4">
                <label class="form-label" for="notes_private_default">
                    @lang('settings.notes_private_default')
                </label>
                <select id="notes_private_default" name="notes_private_default"
                    class="form-select{{ $errors->has('notes_private_default') ? ' is-invalid' : '' }}">
                    <option value="0"
                        @if(usersettings('notes_private_default') === false) selected @endif>
                        @lang('linkace.no')
                    </option>
                    <option value="1"
                        @if(usersettings('notes_private_default') === true) selected @endif>
                        @lang('linkace.yes')
                    </option>
                </select>
                <p class="text-muted small mt-1">@lang('settings.notes_private_default_help')</p>
                @if ($errors->has('notes_private_default'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('notes_private_default') }}
                    </p>
                @endif
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-8 col-md-6">

            <div class="mb-4">
                <label class="form-label" for="lists_private_default">
                    @lang('settings.lists_private_default')
                </label>
                <select id="lists_private_default" name="lists_private_default"
                    class="form-select{{ $errors->has('lists_private_default') ? ' is-invalid' : '' }}">
                    <option value="0"
                        @if(usersettings('lists_private_default') === false) selected @endif>
                        @lang('linkace.no')
                    </option>
                    <option value="1"
                        @if(usersettings('lists_private_default') === true) selected @endif>
                        @lang('linkace.yes')
                    </option>
                </select>
                <p class="text-muted small mt-1">@lang('settings.lists_private_default_help')</p>
                @if ($errors->has('lists_private_default'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('lists_private_default') }}
                    </p>
                @endif
            </div>

        </div>
        <div class="col-12 col-sm-8 col-md-6">

            <div class="mb-4">
                <label class="form-label" for="tags_private_default">
                    @lang('settings.tags_private_default')
                </label>
                <select id="tags_private_default" name="tags_private_default"
                    class="form-select{{ $errors->has('tags_private_default') ? ' is-invalid' : '' }}">
                    <option value="0"
                        @if(usersettings('tags_private_default') === false) selected @endif>
                        @lang('linkace.no')
                    </option>
                    <option value="1"
                        @if(usersettings('tags_private_default') === true) selected @endif>
                        @lang('linkace.yes')
                    </option>
                </select>
                <p class="text-muted small mt-1">@lang('settings.tags_private_default_help')</p>
                @if ($errors->has('tags_private_default'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('tags_private_default') }}
                    </p>
                @endif
            </div>

        </div>
    </div>

</div>
