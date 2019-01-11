<div class="form-group mt-4 mb-4">

    <h5>
        @lang('settings.sharing')
    </h5>

    <p>@lang('settings.sharing_help')</p>

    <div class="sharing-settings">

        @foreach(config('sharing.services') as $key => $details)
            <div class="sharing-checkbox">
                <input type="checkbox" id="share-{{ $key }}" name="share[{{ $key }}]" value="1"
                    @if(old('share.' . $key) ?: usersettings('share_' . $key)) checked @endif>
                <label for="share-{{ $key }}" title="@lang('sharing.service.' . $key)">
                    <i class="fa-fw {{ $details['icon'] }}"></i>
                </label>
            </div>
        @endforeach

    </div>

    <button type="button" class="share-toggle btn btn-sm btn-outline-secondary">
        <i class="fa fa-toggle-on"></i> @lang('settings.sharing_toggle')
    </button>

    @if ($errors->has('timezone'))
        <p class="invalid-feedback" role="alert">
            {{ $errors->first('timezone') }}
        </p>
    @endif
</div>

@push('scripts')
    <script>
        $('.share-toggle').click(function () {
            $('.sharing-checkbox :checkbox').each(function () {
                this.checked = !this.checked;
            });
        });
    </script>
@endpush
