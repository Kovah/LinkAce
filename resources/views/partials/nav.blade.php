<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ auth()->guest() ? route('front') : route('home') }}">
            <img src="{{ asset('assets/img/logo_linkace.svg') }}" alt="@lang('linkace.linkace')"
                width="76" height="28">
        </a>
        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div class="navbar-menu">
        @guest
            <a href="{{ route('login') }}" class="navbar-item">
                @lang('linkace.login')
            </a>
        @else
            <a href="{{ route('links.index') }}" class="navbar-item">
                @lang('link.links')
            </a>
            <a onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                class="navbar-item">
                @lang('linkace.logout')
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endguest
    </div>

</nav>
