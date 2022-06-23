<div {{ $attributes }}>
    <label class="form-label" for="visibility">@lang('linkace.visibility')</label>
    <select id="visibility" name="visibility" class="form-select{{ $errors->has('visibility') ? ' is-invalid' : '' }}">
        <option value="{{ $public }}" {{ $publicSelected ? 'selected' : '' }}>
            @lang('attributes.visibility.' . $public)
        </option>
        <option value="{{ $internal }}" {{ $internalSelected ? 'selected' : '' }}>
            @lang('attributes.visibility.' . $internal)
        </option>
        <option value="{{ $private }}" {{ $privateSelected ? 'selected' : '' }}>
            @lang('attributes.visibility.' . $private)
        </option>
    </select>

    @if ($errors->has('visibility'))
        <p class="invalid-feedback" role="alert">
            {{ $errors->first('visibility') }}
        </p>
    @endif
</div>
