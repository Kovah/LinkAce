<div class="card mt-5">
    <div class="card-header">
        @lang('settings.settings')
    </div>
    <div class="card-body">

        <form action="{{ route('save-settings-system') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="page_title">
                            @lang('settings.page_title')
                        </label>
                        <input type="text" id="page_title" name="page_title" class="form-control"
                            value="{{ old('page_title') ?: systemsettings('page_title') }}">
                        @if ($errors->has('page_title'))
                            <p class="invalid-feedback mt-1" role="alert">
                                {{ $errors->first('page_title') }}
                            </p>
                        @endif
                    </div>

                </div>
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="guest_access_enabled">
                            @lang('settings.guest_access')
                        </label>
                        <select id="guest_access_enabled" name="guest_access_enabled"
                            class="simple-select {{ $errors->has('guest_access_enabled') ? ' is-invalid' : '' }}">
                            <x-forms.yes-no-options :setting="systemsettings('guest_access_enabled')"/>
                        </select>
                        <p class="small text-pale mt-1">@lang('settings.guest_access_help')</p>
                        @if ($errors->has('guest_access_enabled'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('guest_access_enabled') }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-8 col-md-6">
                    <div class="mb-4">
                        <label class="form-label" for="custom_header_content">
                            @lang('settings.custom_header_content')
                        </label>

                        <textarea name="custom_header_content" id="custom_header_content" rows="4"
                            class="form-control{{ $errors->has('custom_header_content') ? ' is-invalid' : '' }}"
                        >{{ old('custom_header_content', systemsettings('custom_header_content')) }}</textarea>
                        <p class="small text-pale mt-1">@lang('settings.custom_header_content_help')</p>

                        @error('custom_header_content')
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('custom_header_content') }}
                        </p>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <x-icon.save class="me-2"/> @lang('settings.save_settings')
            </button>

        </form>

    </div>
</div>
