<div class="navbar navbar-dark brand-only bg-primary shadow-sm d-block d-md-none text-center">
    <a class="navbar-brand d-inline-block" href="{{ route('dashboard') }}">
        {!! displaySVG(public_path('assets/img/logo_linkace.svg')) !!}
    </a>
</div>
<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
    <div class="container px-0 px-lg-3">
        <a class="navbar-brand d-none d-md-inline-block" href="{{ route('dashboard') }}">
            {!! displaySVG(public_path('assets/img/logo_linkace.svg')) !!}
        </a>

        @auth
            <ul class="navbar-nav flex-row">
                <li class="nav-item">
                    <a href="{{ route('links.create') }}" class="nav-link">
                        @lang('link.add')
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('links.index') }}" class="nav-link pl-3 pl-md-2">
                        @lang('link.all_links')
                    </a>
                </li>
            </ul>
        @endauth

        <button class="navbar-toggler ml-auto d-flex align-items-center d-md-none" type="button"
            data-toggle="collapse" data-target="#navbar-content"
            aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation">
            <small class="mr-3">@lang('linkace.menu')</small> <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar-content">
            @auth
                <ul class="navbar-nav mr-auto">

                    <li class="nav-divider"></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbar-lists-dd" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('list.lists')
                            <i class="fas fa-caret-down fa-fw"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbar-lists-dd">
                            <a href="{{ route('lists.index') }}" class="dropdown-item">
                                @lang('list.all_lists')
                            </a>
                            <a href="{{ route('lists.create') }}" class="dropdown-item">
                                @lang('list.add')
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbar-tags-dd" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('tag.tags')
                            <i class="fas fa-caret-down fa-fw"></i>
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
                @auth
                    <li class="nav-item">
                        <a href="{{ route('get-search') }}" class="nav-link" title="@lang('search.search')">
                            <span class="d-md-none">@lang('search.search')</span>
                            <i class="fas fa-search fa-fw"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('get-trash') }}" class="nav-link" title="@lang('trash.trash')">
                            <span class="d-md-none">@lang('trash.trash')</span>
                            <i class="fas fa-trash-alt fa-fw"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbar-user-dd" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ auth()->user()->name }}
                            <i class="fas fa-caret-down fa-fw"></i>
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
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            @lang('linkace.login')
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
