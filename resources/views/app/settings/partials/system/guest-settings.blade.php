<div class="card mt-5">
    <div class="card-header">
        @lang('settings.guest_settings')
    </div>
    <div class="card-body">

        <p>@lang('settings.guest_settings_info')</p>

        <form action="{{ route('save-settings-system') }}" method="POST">
            @csrf

            <div class="row mt-4">
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="form-group">
                        <label for="guest_listitem_count">
                            @lang('settings.listitem_count')
                        </label>
                        <select id="guest_listitem_count" name="guest_listitem_count"
                            class="custom-select{{ $errors->has('guest_listitem_count') ? ' is-invalid' : '' }}">
                            @foreach(config('linkace.listitem_count_values') as $item_count)
                                <option value="{{ $item_count }}"
                                    @if(systemsettings('guest_listitem_count') == $item_count) selected @endif>
                                    {{ $item_count }} @lang('linkace.entries')
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('guest_listitem_count'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('guest_listitem_count') }}
                            </p>
                        @endif
                    </div>

                </div>
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="form-group">
                        <label for="guest_link_display_mode">
                            @lang('settings.display_mode')
                        </label>
                        <select id="guest_link_display_mode" name="guest_link_display_mode"
                            class="custom-select{{ $errors->has('guest_link_display_mode') ? ' is-invalid' : '' }}">
                            <option value="{{ Link::DISPLAY_LIST_DETAILED }}"
                                @if((int)systemsettings()->get('guest_link_display_mode') === Link::DISPLAY_LIST_DETAILED)
                                selected
                                @endif>
                                @lang('settings.display_mode_list_detailed')
                            </option>
                            <option value="{{ Link::DISPLAY_LIST_SIMPLE }}"
                                @if((int)systemsettings()->get('guest_link_display_mode') === Link::DISPLAY_LIST_SIMPLE)
                                selected
                                @endif>
                                @lang('settings.display_mode_list_simple')
                            </option>
                            <option value="{{ Link::DISPLAY_CARDS }}"
                                @if((int)systemsettings()->get('guest_link_display_mode') === Link::DISPLAY_CARDS)
                                selected
                                @endif>
                                @lang('settings.display_mode_cards')
                            </option>
                        </select>
                        @if ($errors->has('guest_link_display_mode'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('guest_link_display_mode') }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-8 col-md-6">

                    <div class="form-group">
                        <label for="guest_links_new_tab">
                            @lang('settings.links_new_tab')
                        </label>
                        <select id="guest_links_new_tab" name="guest_links_new_tab"
                            class="simple-select {{ $errors->has('guest_links_new_tab') ? ' is-invalid' : '' }}">
                            <option value="0" @if(systemsettings('guest_links_new_tab') === '0') selected @endif>
                                @lang('linkace.no')
                            </option>
                            <option value="1" @if(systemsettings('guest_links_new_tab') === '1') selected @endif>
                                @lang('linkace.yes')
                            </option>
                        </select>
                        @if ($errors->has('guest_links_new_tab'))
                            <p class="invalid-feedback" role="alert">
                                {{ $errors->first('guest_links_new_tab') }}
                            </p>
                        @endif
                    </div>

                </div>
                <div class="col-12 col-sm-8 col-md-6"></div>
            </div>

            @include('app.settings.partials.system.guest.dark-mode')

            @include('app.settings.partials.system.guest.sharing')

            <button type="submit" class="btn btn-primary">
                <x-icon.save class="mr-2"/> @lang('settings.save_settings')
            </button>

        </form>

    </div>
</div>
