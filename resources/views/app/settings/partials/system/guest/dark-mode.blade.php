<div class="form-group my-5">

    <h5>
        @lang('settings.darkmode')
    </h5>

    <p class="my-3 small">@lang('settings.darkmode_help')</p>

    <div class="row">
        <div class="col-12 col-sm-8 col-md-6">

            <select id="guest_darkmode_setting" name="guest_darkmode_setting"
                class="custom-select{{ $errors->has('guest_darkmode_setting') ? ' is-invalid' : '' }}">
                <option value="0"
                    @if(systemsettings('guest_darkmode_setting') === '0') selected @endif>
                    @lang('settings.darkmode_disabled')
                </option>
                <option value="1"
                    @if(systemsettings('guest_darkmode_setting') === '1') selected @endif>
                    @lang('settings.darkmode_permanent')
                </option>
                <option value="2"
                    @if(systemsettings('guest_darkmode_setting') === '2') selected @endif>
                    @lang('settings.darkmode_auto')
                </option>
            </select>
            @if ($errors->has('guest_darkmode_setting'))
                <p class="invalid-feedback" role="alert">
                    {{ $errors->first('guest_darkmode_setting') }}
                </p>
            @endif

        </div>
        <div class="col-12 col-sm-8 col-md-6"></div>
    </div>

</div>
