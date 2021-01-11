@auth
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
            <span class="d-sm-none">
                <x-icon.cog class="fw"/>
                <span class="sr-only">@lang('settings.settings')</span>
            </span>
            <x-icon.caret-down class="fw"/>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('get-usersettings') }}" class="dropdown-item">
                @lang('settings.settings')
            </a>
            <a href="#" class="dropdown-item cursor-pointer text-danger"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                @lang('linkace.logout')
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{ route('get-import') }}" class="dropdown-item">
                @lang('import.import')
            </a>
            <a href="{{ route('get-export') }}" class="dropdown-item">
                @lang('export.export')
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{ route('get-systemsettings') }}" class="dropdown-item">
                @lang('settings.system_settings')
            </a>
            <a href="{{ route('system-logs') }}" class="dropdown-item">
                @lang('linkace.system_logs')
            </a>
        </div>
    </li>
@else
    <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">
            @lang('linkace.login')
        </a>
    </li>
@endauth
