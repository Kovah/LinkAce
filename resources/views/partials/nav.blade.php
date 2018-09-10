<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top px-0">
    <div class="container">
        <a class="navbar-brand" href="{{ auth()->guest() ? route('front') : route('dashboard') }}">
            <img src="{{ asset('assets/img/logo_linkace.svg') }}" alt="@lang('linkace.linkace')"
                width="81" height="30">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @auth
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbar-links-dd" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('link.links')
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbar-links-dd">
                            <a href="{{ route('links.index') }}" class="dropdown-item">
                                @lang('link.all_links')
                            </a>
                            <a href="{{ route('links.create') }}" class="dropdown-item">
                                @lang('link.add')
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbar-categories-dd" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('category.categories')
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbar-categories-dd">
                            <a href="{{ route('categories.index') }}" class="dropdown-item">
                                @lang('category.all_categories')
                            </a>
                            <a href="{{ route('categories.create') }}" class="dropdown-item">
                                @lang('category.add')
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbar-tags-dd" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('tag.tags')
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbar-tags-dd">
                            <a href="{{ route('tags.index') }}" class="dropdown-item">
                                @lang('tag.all_tags')
                            </a>
                            <a href="{{ route('tags.create') }}" class="dropdown-item">
                                @lang('tag.add')
                            </a>
                        </div>
                    </li>
                </ul>
            @endauth
            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            @lang('linkace.login')
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('get-search') }}" class="nav-link">
                            @lang('search.search')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            @lang('linkace.logout')
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
