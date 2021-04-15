<div class="form-group my-5">
    <h5>
        @lang('settings.privacy')
    </h5>

    <div class="row">
        <div class="col-12 col-sm-8 col-md-6">

            <div class="form-group">
                <label for="links_private_default">
                    @lang('settings.links_private_default')
                </label>
                <select id="links_private_default" name="links_private_default"
                    class="custom-select{{ $errors->has('links_private_default') ? ' is-invalid' : '' }}">
                    <option value="0"
                        @if($user->settings()->get('links_private_default') === '0') selected @endif>
                        @lang('linkace.no')
                    </option>
                    <option value="1"
                        @if($user->settings()->get('links_private_default') === '1') selected @endif>
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

            <div class="form-group">
                <label for="notes_private_default">
                    @lang('settings.notes_private_default')
                </label>
                <select id="notes_private_default" name="notes_private_default"
                    class="custom-select{{ $errors->has('notes_private_default') ? ' is-invalid' : '' }}">
                    <option value="0"
                        @if($user->settings()->get('notes_private_default') === '0') selected @endif>
                        @lang('linkace.no')
                    </option>
                    <option value="1"
                        @if($user->settings()->get('notes_private_default') === '1') selected @endif>
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

            <div class="form-group">
                <label for="lists_private_default">
                    @lang('settings.lists_private_default')
                </label>
                <select id="lists_private_default" name="lists_private_default"
                    class="custom-select{{ $errors->has('lists_private_default') ? ' is-invalid' : '' }}">
                    <option value="0"
                        @if($user->settings()->get('lists_private_default') === '0') selected @endif>
                        @lang('linkace.no')
                    </option>
                    <option value="1"
                        @if($user->settings()->get('lists_private_default') === '1') selected @endif>
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

            <div class="form-group">
                <label for="tags_private_default">
                    @lang('settings.tags_private_default')
                </label>
                <select id="tags_private_default" name="tags_private_default"
                    class="custom-select{{ $errors->has('tags_private_default') ? ' is-invalid' : '' }}">
                    <option value="0"
                        @if($user->settings()->get('tags_private_default') === '0') selected @endif>
                        @lang('linkace.no')
                    </option>
                    <option value="1"
                        @if($user->settings()->get('tags_private_default') === '1') selected @endif>
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
