<?php

use App\Enums\ModelAttribute;

$settings = [
    'links_default_visibility',
    'notes_default_visibility',
    'lists_default_visibility',
    'tags_default_visibility',
];
?>
<div class="mb-3 my-5">
    <h5 class="mb-3">
        @lang('settings.privacy')
    </h5>

    <p>@lang('settings.default_visibility_help')</p>

    <div class="row">
        @foreach($settings as $setting)
            <div class="col-12 col-sm-8 col-md-6">

                <div class="mb-4">
                    <label class="form-label" for="{{ $setting }}">
                        @lang('settings.' . $setting)
                    </label>
                    <select id="{{ $setting }}" name="{{ $setting }}"
                        class="form-select{{ $errors->has($setting) ? ' is-invalid' : '' }}">
                        <option value="{{ ModelAttribute::VISIBILITY_PUBLIC }}"
                            {{ usersettings($setting) === ModelAttribute::VISIBILITY_PUBLIC ? 'selected' : '' }}>
                            @lang('attributes.visibility.' . ModelAttribute::VISIBILITY_PUBLIC)
                        </option>
                        <option value="{{ ModelAttribute::VISIBILITY_INTERNAL }}"
                            {{ usersettings($setting) === ModelAttribute::VISIBILITY_INTERNAL ? 'selected' : '' }}>
                            @lang('attributes.visibility.' . ModelAttribute::VISIBILITY_INTERNAL)
                        </option>
                        <option value="{{ ModelAttribute::VISIBILITY_PRIVATE }}"
                            {{ usersettings($setting) === ModelAttribute::VISIBILITY_PRIVATE ? 'selected' : '' }}>
                            @lang('attributes.visibility.' . ModelAttribute::VISIBILITY_PRIVATE)
                        </option>
                    </select>
                    @if ($errors->has($setting))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first($setting) }}
                        </p>
                    @endif
                </div>

            </div>
        @endforeach
    </div>

</div>
