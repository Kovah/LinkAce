<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ auth()->guest() ? route('front') : route('dashboard') }}">
            {!! displaySVG(public_path('assets/img/logo_linkace.svg')) !!}
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-content"
            aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar-content">
            @auth
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="{{ route('links.create') }}" class="nav-link">
                            @lang('link.add')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('links.index') }}" class="nav-link">
                            @lang('link.all_links')
                        </a>
                    </li>
                    <li class="nav-divider"></li>
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
                        <a href="{{ route('get-search') }}" class="nav-link" title="@lang('search.search')">
                            <span class="d-md-none">@lang('search.search')</span>
                            <i class="fas fa-search fa-fw mx-1"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('get-trash') }}" class="nav-link" title="@lang('trash.trash')">
                            <span class="d-md-none">@lang('trash.trash')</span>
                            <i class="fas fa-trash-alt fa-fw mx-1"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbar-user-dd" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-user-dd">
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
                            <a href="{{ route('get-sysstemsettings') }}" class="dropdown-item">
                                @lang('settings.system_settings')
                            </a>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
