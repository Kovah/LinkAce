<div class="mb-3 my-5">

    <h5>
        @lang('settings.darkmode_setting')
    </h5>

    <p class="my-3 small">@lang('settings.darkmode_help')</p>

    <div class="row">
        <div class="col-12 col-sm-8 col-md-6">

            <select id="darkmode_setting" name="darkmode_setting"
                class="form-select{{ $errors->has('darkmode_setting') ? ' is-invalid' : '' }}">
                <option value="0" @selected(usersettings('darkmode_setting') === 0)>
                    @lang('settings.darkmode_disabled')
                </option>
                <option value="1" @selected(usersettings('darkmode_setting') === 1)>
                    @lang('settings.darkmode_permanent')
                </option>
                <option value="2" @selected(usersettings('darkmode_setting') === 2)>
                    @lang('settings.darkmode_auto')
                </option>
            </select>
            @if ($errors->has('darkmode_setting'))
                <p class="invalid-feedback" role="alert">
                    {{ $errors->first('darkmode_setting') }}
                </p>
            @endif

        </div>
        <div class="col-12 col-sm-8 col-md-6"></div>
    </div>

</div>
