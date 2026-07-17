<div class="navbar navbar-dark brand-only bg-primary shadow-sm d-block d-md-none text-center">
    <a class="navbar-brand d-inline-block" href="{{ route('front') }}">
        <x-icon.linkace/>
    </a>
</div>
<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand{{ systemsettings('logo_text') ? ' custom-brand' : '' }}" href="{{ route('front') }}">
            @if(systemsettings('logo_text'))
                {{ systemsettings('logo_text') }}
            @else
                <x-icon.linkace/>
            @endif
        </a>

        <ul class="navbar-nav flex-row">
            <li class="nav-item">
                <a href="{{ route('guest.links.index') }}" class="nav-link">
                    @lang('link.links')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('guest.lists.index') }}" class="nav-link ps-3 ps-md-2">
                    @lang('list.lists')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('guest.tags.index') }}" class="nav-link ps-3 ps-md-2">
                    @lang('tag.tags')
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a href="{{ route('guest.search') }}" class="nav-link" title="@lang('search.search')">
                    <x-icon.search class="fw"/>
                    <span class="visually-hidden">@lang('search.search')</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">
                    @lang('linkace.login')
                </a>
            </li>
        </ul>
    </div>
</nav>
