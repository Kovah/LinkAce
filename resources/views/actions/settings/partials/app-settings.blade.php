<div class="card mt-4">
    <div class="card-header">
        @lang('settings.user_settings')
    </div>
    <div class="card-body">

        <form action="{{ route('save-settings-app') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col">

                    <div class="form-group">
                        <label for="timezone">
                            @lang('settings.timezone')
                        </label>
                        <select id="timezone" name="timezone"
                            class="simple-select {{ $errors->has('timezone') ? ' is-invalid' : '' }}">
                            @foreach(timezone_identifiers_list() as $key => $zone)
                                <option value="{{ $zone }}"
                                    @if($user->settings()->get('timezone') === $zone) selected @endif>
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
                <div class="col">

                    <div class="form-group">
                        <label for="links_new_tab">
                            @lang('settings.links_new_tab')
                        </label>
                        <select id="links_new_tab" name="links_new_tab"
                            class="simple-select {{ $errors->has('links_new_tab') ? ' is-invalid' : '' }}">
                            <option value="0" @if($user->settings()->get('links_new_tab') === '0') selected @endif>
                                @lang('linkace.no')
                            </option>
                            <option value="1" @if($user->settings()->get('links_new_tab') === '1') selected @endif>
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
            </div>

            <div class="row">
                <div class="col">

                    <div class="form-group">
                        <label for="date_format">
                            @lang('settings.date_format')
                        </label>
                        <select id="date_format" name="date_format"
                            class="custom-select{{ $errors->has('date_format') ? ' is-invalid' : '' }}">
                            @foreach(config('linkace.formats.date') as $date_format)
                                <option value="{{ $date_format }}"
                                    @if($user->settings()->get('date_format') === $date_format) selected @endif>
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
                <div class="col">

                    <div class="form-group">
                        <label for="time_format">
                            @lang('settings.time_format')
                        </label>
                        <select id="time_format" name="time_format"
                            class="custom-select{{ $errors->has('time_format') ? ' is-invalid' : '' }}">
                            @foreach(config('linkace.formats.time') as $time_format)
                                <option value="{{ $time_format }}"
                                    @if($user->settings()->get('time_format') === $time_format) selected @endif>
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

            <div class="row">
                <div class="col">

                    <div class="form-group">
                        <label for="listitem_count">
                            @lang('settings.listitem_count')
                        </label>
                        <select id="listitem_count" name="listitem_count"
                            class="custom-select{{ $errors->has('listitem_count') ? ' is-invalid' : '' }}">
                            @foreach(config('linkace.listitem_count_values') as $item_count)
                                <option value="{{ $item_count }}"
                                    @if($user->settings()->get('listitem_count') == $item_count) selected @endif>
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
                <div class="col">

                    <div class="form-group">
                        <label for="link_display_mode">
                            @lang('settings.display_mode')
                        </label>
                        <select id="link_display_mode" name="link_display_mode"
                            class="custom-select{{ $errors->has('link_display_mode') ? ' is-invalid' : '' }}">
                            <option value="{{ Link::DISPLAY_LIST_DETAILED }}"
                                @if((int)$user->settings()->get('link_display_mode') === Link::DISPLAY_LIST_DETAILED)
                                selected
                                @endif>
                                @lang('settings.display_mode_list_detailed')
                            </option>
                            <option value="{{ Link::DISPLAY_LIST_SIMPLE }}"
                                @if((int)$user->settings()->get('link_display_mode') === Link::DISPLAY_LIST_SIMPLE)
                                selected
                                @endif>
                                @lang('settings.display_mode_list_simple')
                            </option>
                            <option value="{{ Link::DISPLAY_CARDS }}"
                                @if((int)$user->settings()->get('link_display_mode') === Link::DISPLAY_CARDS)
                                selected
                                @endif>
                                @lang('settings.display_mode_cards')
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

            <div class="form-group my-5">
                <h5>
                    @lang('settings.privacy')
                </h5>

                <div class="row">
                    <div class="col">

                        <div class="form-group">
                            <label for="links_private_default">
                                @lang('settings.links_private_default')
                            </label>
                            <select id="links_private_default" name="links_private_default"
                                class="custom-select{{ $errors->has('links_private_default') ? ' is-invalid' : '' }}">
                                <option value="0" @if($user->settings()->get('links_private_default') === '0') selected @endif>
                                    @lang('linkace.no')
                                </option>
                                <option value="1" @if($user->settings()->get('links_private_default') === '1') selected @endif>
                                    @lang('linkace.yes')
                                </option>
                            </select>
                            <p class="text-muted small">@lang('settings.links_private_default_help')</p>
                            @if ($errors->has('links_private_default'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('links_private_default') }}
                                </p>
                            @endif
                        </div>

                    </div>
                    <div class="col">

                        <div class="form-group">
                            <label for="notes_private_default">
                                @lang('settings.notes_private_default')
                            </label>
                            <select id="notes_private_default" name="notes_private_default"
                                class="custom-select{{ $errors->has('notes_private_default') ? ' is-invalid' : '' }}">
                                <option value="0"
                                    @if($user->settings()->get('notes_private_default') === '0') selected @endif>
                                    @lang('linkace.no')
                                </option>
                                <option value="1"
                                    @if($user->settings()->get('notes_private_default') === '1') selected @endif>
                                    @lang('linkace.yes')
                                </option>
                            </select>
                            <p class="text-muted small">@lang('settings.notes_private_default_help')</p>
                            @if ($errors->has('notes_private_default'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('notes_private_default') }}
                                </p>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col">

                        <div class="form-group">
                            <label for="lists_private_default">
                                @lang('settings.lists_private_default')
                            </label>
                            <select id="lists_private_default" name="lists_private_default"
                                class="custom-select{{ $errors->has('lists_private_default') ? ' is-invalid' : '' }}">
                                <option value="0" @if($user->settings()->get('lists_private_default') === '0') selected @endif>
                                    @lang('linkace.no')
                                </option>
                                <option value="1" @if($user->settings()->get('lists_private_default') === '1') selected @endif>
                                    @lang('linkace.yes')
                                </option>
                            </select>
                            <p class="text-muted small">@lang('settings.lists_private_default_help')</p>
                            @if ($errors->has('lists_private_default'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('lists_private_default') }}
                                </p>
                            @endif
                        </div>

                    </div>
                    <div class="col">

                        <div class="form-group">
                            <label for="tags_private_default">
                                @lang('settings.tags_private_default')
                            </label>
                            <select id="tags_private_default" name="tags_private_default"
                                class="custom-select{{ $errors->has('tags_private_default') ? ' is-invalid' : '' }}">
                                <option value="0"
                                    @if($user->settings()->get('tags_private_default') === '0') selected @endif>
                                    @lang('linkace.no')
                                </option>
                                <option value="1"
                                    @if($user->settings()->get('tags_private_default') === '1') selected @endif>
                                    @lang('linkace.yes')
                                </option>
                            </select>
                            <p class="text-muted small">@lang('settings.tags_private_default_help')</p>
                            @if ($errors->has('tags_private_default'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('tags_private_default') }}
                                </p>
                            @endif
                        </div>

                    </div>
                </div>

            </div>

            <div class="form-group my-5">

                <h5>
                    @lang('settings.darkmode')
                </h5>

                <label for="darkmode_setting">
                    @lang('settings.darkmode_help')
                </label>

                <div class="row">
                    <div class="col">

                        <select id="darkmode_setting" name="darkmode_setting"
                            class="custom-select{{ $errors->has('darkmode_setting') ? ' is-invalid' : '' }}">
                            <option value="0"
                                @if($user->settings()->get('darkmode_setting') === '0') selected @endif>
                                @lang('settings.darkmode_disabled')
                            </option>
                            <option value="1"
                                @if($user->settings()->get('darkmode_setting') === '1') selected @endif>
                                @lang('settings.darkmode_permanent')
                            </option>
                            <option value="2"
                                @if($user->settings()->get('darkmode_setting') === '2') selected @endif>
                                @lang('settings.darkmode_auto')
                            </option>
                        </select>
                        @if ($errors->has('darkmode_setting'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('darkmode_setting') }}
                            </p>
                        @endif

                    </div>
                    <div class="col"></div>
                </div>

            </div>

            @include('actions.settings.partials.sharing')

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i> @lang('settings.save_settings')
            </button>

        </form>

    </div>
</div>
