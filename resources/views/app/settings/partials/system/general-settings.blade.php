<div class="card mt-5">
    <div class="card-header">
        @lang('settings.system_settings')
    </div>
    <div class="card-body">

        <form action="{{ route('save-settings-system') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="system_page_title">
                            @lang('settings.sys_page_title')
                        </label>
                        <input type="text" id="system_page_title" name="system_page_title" class="form-control"
                            value="{{ old('system_page_title') ?: systemsettings('system_page_title') }}">
                        @if ($errors->has('system_page_title'))
                            <p class="invalid-feedback mt-1" role="alert">
                                {{ $errors->first('system_page_title') }}
                            </p>
                        @endif
                    </div>

                </div>
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="system_guest_access">
                            @lang('settings.sys_guest_access')
                        </label>
                        <select id="system_guest_access" name="system_guest_access"
                            class="simple-select {{ $errors->has('system_guest_access') ? ' is-invalid' : '' }}">
                            <option value="0"
                                @if(systemsettings('system_guest_access') == 0) selected="selected" @endif>
                                @lang('linkace.no')
                            </option>
                            <option value="1"
                                @if(systemsettings('system_guest_access') == 1) selected="selected" @endif>
                                @lang('linkace.yes')
                            </option>
                        </select>
                        <p class="small text-muted mt-1">@lang('settings.sys_guest_access_help')</p>
                        @if ($errors->has('system_guest_access'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('system_guest_access') }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-8 col-md-6">
                    <div class="mb-4">
                        <label class="form-label" for="system_custom_header_content">
                            @lang('settings.sys_custom_header_content')
                        </label>

                        <textarea name="system_custom_header_content" id="system_custom_header_content" rows="4"
                            class="form-control{{ $errors->has('system_custom_header_content') ? ' is-invalid' : '' }}"
                        >{{ old('system_custom_header_content', systemsettings('system_custom_header_content')) }}</textarea>
                        <p class="small text-muted mt-1">@lang('settings.sys_custom_header_content_help')</p>

                        @error('system_custom_header_content')
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('system_custom_header_content') }}
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
