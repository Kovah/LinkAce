<div class="card mt-5">
    <div class="card-header">
        @lang('settings.user_settings')
    </div>
    <div class="card-body">

        <form action="{{ route('save-settings-app') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="locale">
                            @lang('settings.locale')
                        </label>
                        <select id="locale" name="locale"
                            class="simple-select {{ $errors->has('locale') ? ' is-invalid' : '' }}">
                            @foreach(config('app.available_locales') as $key => $locale)
                                <option value="{{ $key }}"
                                    @if(usersettings('locale') === $key) selected @endif>
                                    {{ $locale }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('locale'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('locale') }}
                            </p>
                        @endif
                    </div>

                </div>
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="timezone">
                            @lang('settings.timezone')
                        </label>
                        <select id="timezone" name="timezone"
                            class="simple-select {{ $errors->has('timezone') ? ' is-invalid' : '' }}">
                            @foreach(timezone_identifiers_list() as $key => $zone)
                                <option value="{{ $zone }}"
                                    @if(usersettings('timezone') === $zone) selected @endif>
                                    {{ $zone }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('timezone'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('timezone') }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="date_format">
                            @lang('settings.date_format')
                        </label>
                        <select id="date_format" name="date_format"
                            class="form-select{{ $errors->has('date_format') ? ' is-invalid' : '' }}">
                            @foreach(config('linkace.formats.date') as $date_format)
                                <option value="{{ $date_format }}"
                                    @if(usersettings('date_format') === $date_format) selected @endif>
                                    {{ $date_format }} ({{ date($date_format) }})
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('date_format'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('date_format') }}
                            </p>
                        @endif
                    </div>

                </div>
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="time_format">
                            @lang('settings.time_format')
                        </label>
                        <select id="time_format" name="time_format"
                            class="form-select{{ $errors->has('time_format') ? ' is-invalid' : '' }}">
                            @foreach(config('linkace.formats.time') as $time_format)
                                <option value="{{ $time_format }}"
                                    @if(usersettings('time_format') === $time_format) selected @endif>
                                    {{ $time_format }} ({{ date($time_format) }})
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('time_format'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('time_format') }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="listitem_count">
                            @lang('settings.listitem_count')
                        </label>
                        <select id="listitem_count" name="listitem_count"
                            class="form-select{{ $errors->has('listitem_count') ? ' is-invalid' : '' }}">
                            @foreach(config('linkace.listitem_count_values') as $item_count)
                                <option value="{{ $item_count }}"
                                    @if(usersettings('listitem_count') === $item_count) selected @endif>
                                    {{ $item_count }} @lang('linkace.entries')
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('listitem_count'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('listitem_count') }}
                            </p>
                        @endif
                    </div>

                </div>
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="link_display_mode">
                            @lang('settings.display_mode')
                        </label>
                        <select id="link_display_mode" name="link_display_mode"
                            class="form-select{{ $errors->has('link_display_mode') ? ' is-invalid' : '' }}">
                            <option value="{{ Link::DISPLAY_LIST_DETAILED }}"
                                @if(usersettings('link_display_mode') === Link::DISPLAY_LIST_DETAILED)
                                selected
                                @endif>
                                @lang('settings.display_mode_list_detailed')
                            </option>
                            <option value="{{ Link::DISPLAY_LIST_SIMPLE }}"
                                @if(usersettings('link_display_mode') === Link::DISPLAY_LIST_SIMPLE)
                                selected
                                @endif>
                                @lang('settings.display_mode_list_simple')
                            </option>
                            <option value="{{ Link::DISPLAY_CARDS }}"
                                @if(usersettings('link_display_mode') === Link::DISPLAY_CARDS)
                                selected
                                @endif>
                                @lang('settings.display_mode_cards')
                            </option>
                            <option value="{{ Link::DISPLAY_CARDS_DETAILED }}"
                                @if(usersettings('link_display_mode') === Link::DISPLAY_CARDS_DETAILED)
                                selected
                                @endif>
                                @lang('settings.display_mode_cards_detailed')
                            </option>
                        </select>
                        @if ($errors->has('link_display_mode'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('link_display_mode') }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="links_new_tab">
                            @lang('settings.links_new_tab')
                        </label>
                        <select id="links_new_tab" name="links_new_tab"
                            class="simple-select {{ $errors->has('links_new_tab') ? ' is-invalid' : '' }}">
                            <option value="0" @if(usersettings('links_new_tab') === false) selected @endif>
                                @lang('linkace.no')
                            </option>
                            <option value="1" @if(usersettings('links_new_tab') === true) selected @endif>
                                @lang('linkace.yes')
                            </option>
                        </select>
                        @if ($errors->has('links_new_tab'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('links_new_tab') }}
                            </p>
                        @endif
                    </div>

                </div>
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="mb-4">
                        <label class="form-label" for="links_new_tab">
                            @lang('settings.markdown_for_text')
                        </label>
                        <select id="markdown_for_text" name="markdown_for_text"
                            class="simple-select {{ $errors->has('markdown_for_text') ? ' is-invalid' : '' }}">
                            <option value="1" @if(usersettings('markdown_for_text') === true) selected @endif>
                                @lang('linkace.yes')
                            </option>
                            <option value="0" @if(usersettings('markdown_for_text') === false) selected @endif>
                                @lang('linkace.no')
                            </option>
                        </select>
                        @if ($errors->has('markdown_for_text'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('markdown_for_text') }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>

            @include('app.settings.partials.user.app-settings.archive-backups')

            @include('app.settings.partials.user.app-settings.privacy')

            @include('app.settings.partials.user.app-settings.dark-mode')

            @include('app.settings.partials.user.app-settings.sharing')

            <button type="submit" class="btn btn-primary">
                <x-icon.save class="me-2"/> @lang('settings.save_settings')
            </button>

        </form>

    </div>
</div>
