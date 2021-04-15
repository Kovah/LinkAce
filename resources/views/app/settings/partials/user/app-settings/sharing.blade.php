<div class="form-group my-5">

    <h5>
        @lang('settings.sharing')
    </h5>

    <p>@lang('settings.sharing_help')</p>

    <div class="sharing-settings">

        @foreach(config('sharing.services') as $key => $details)
            <div class="sharing-checkbox">
                <input type="checkbox" id="share-{{ $key }}" name="share[{{ $key }}]" value="1"
                    class="sharing-checkbox-input"
                    @if(old('share.' . $key) ?: usersettings('share_' . $key)) checked @endif>
                <label for="share-{{ $key }}" title="@lang('sharing.service.' . $key)">
                    <x-dynamic-component :component="$details['icon']" class="fw" />
                    <span class="sr-only">@lang('sharing.service.' . $key)</span>
                </label>
            </div>
        @endforeach

    </div>

    <button type="button" class="share-toggle btn btn-sm btn-outline-secondary">
        <x-icon.toggle-on class="fw mr-2"/> @lang('settings.sharing_toggle')
    </button>

    @if ($errors->has('timezone'))
        <p class="invalid-feedback" role="alert">
            {{ $errors->first('timezone') }}
        </p>
    @endif
</div>
