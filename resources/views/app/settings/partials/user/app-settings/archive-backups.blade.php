<div class="form-group my-5">
    <h5>
        @lang('settings.archive_backups')
    </h5>

    <p class="my-3 small">@lang('settings.archive_backups_help')</p>

    <div class="row">
        <div class="col-12 col-sm-8 col-md-6">

            <div class="form-group">
                <label for="archive_backups_enabled">
                    @lang('settings.archive_backups_enabled')
                </label>
                <select id="archive_backups_enabled" name="archive_backups_enabled"
                    class="custom-select{{ $errors->has('archive_backups_enabled') ? ' is-invalid' : '' }}">
                    <option value="1"
                        @if($user->settings()->get('archive_backups_enabled') === '1') selected @endif>
                        @lang('linkace.yes')
                    </option>
                    <option value="0"
                        @if($user->settings()->get('archive_backups_enabled') === '0') selected @endif>
                        @lang('linkace.no')
                    </option>
                </select>
                <p class="text-muted small mt-1">@lang('settings.archive_backups_enabled_help')</p>
                @if ($errors->has('archive_backups_enabled'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('archive_backups_enabled') }}
                    </p>
                @endif
            </div>

        </div>
        <div class="col-12 col-sm-8 col-md-6">

            <div class="form-group">
                <label for="archive_private_backups_enabled">
                    @lang('settings.archive_private_backups_enabled')
                </label>
                <select id="archive_private_backups_enabled"
                    name="archive_private_backups_enabled"
                    class="custom-select{{ $errors->has('archive_private_backups_enabled') ? ' is-invalid' : '' }}">
                    <option value="1"
                        @if($user->settings()->get('archive_private_backups_enabled') === '1') selected @endif>
                        @lang('linkace.yes')
                    </option>
                    <option value="0"
                        @if($user->settings()->get('archive_private_backups_enabled') === '0') selected @endif>
                        @lang('linkace.no')
                    </option>
                </select>
                <p class="text-muted small mt-1">@lang('settings.archive_private_backups_enabled_help')</p>
                @if ($errors->has('archive_private_backups_enabled'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('archive_private_backups_enabled') }}
                    </p>
                @endif
            </div>

        </div>
    </div>

</div>
