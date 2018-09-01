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
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    @lang('link.links')
                </a>
                <div class="navbar-dropdown">
                    <a href="{{ route('links.index') }}" class="navbar-item">
                        @lang('link.all_links')
                    </a>
                    <a href="{{ route('links.create') }}" class="navbar-item">
                        @lang('link.add')
                    </a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    @lang('category.categories')
                </a>
                <div class="navbar-dropdown">
                    <a href="{{ route('categories.index') }}" class="navbar-item">
                        @lang('category.all_categories')
                    </a>
                    <a href="{{ route('categories.create') }}" class="navbar-item">
                        @lang('category.add')
                    </a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    @lang('tag.tags')
                </a>
                <div class="navbar-dropdown">
                    <a href="{{ route('tags.index') }}" class="navbar-item">
                        @lang('tag.all_tags')
                    </a>
                    <a href="{{ route('tags.create') }}" class="navbar-item">
                        @lang('tag.add')
                    </a>
                </div>
            </div>

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
