<nav class="navbar navbar-dark navbar-expand bg-primary shadow-sm d-none d-md-flex">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <x-icon.linkace/>
        </a>
        @auth
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="{{ route('links.index') }}" class="nav-link ps-3 ps-md-2">
                        @lang('link.links')
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('links.create') }}" class="nav-link ps-0" title="@lang('link.add')">
                        <x-icon.plus/>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('lists.index') }}" class="nav-link ps-3 ps-md-2">
                        @lang('list.lists')
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('lists.create') }}" class="nav-link ps-0" title="@lang('list.add')">
                        <x-icon.plus/>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tags.index') }}" class="nav-link ps-3 ps-md-2">
                        @lang('tag.tags')
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tags.create') }}" class="nav-link ps-0" title="@lang('tag.add')">
                        <x-icon.plus/>
                    </a>
                </li>
            </ul>

        @endauth

        <ul class="navbar-nav ms-auto">
            @auth
                <li class="nav-item">
                    <a href="{{ route('get-search') }}" class="nav-link" title="@lang('search.search')">
                        <x-icon.search class="fw"/>
                        <span class="visually-hidden">@lang('search.search')</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('get-trash') }}" class="nav-link" title="@lang('trash.trash')">
                        <x-icon.trash class="fw"/>
                        <span class="visually-hidden">@lang('trash.trash')</span>
                    </a>
                </li>
            @endauth
            @include('partials.nav-user')
        </ul>
    </div>
</nav>

<div class="navbar navbar-dark navbar-expand brand-only bg-primary shadow-sm d-md-none">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <x-icon.linkace/>
        </a>
        <ul class="navbar-nav ms-auto">
            @include('partials.nav-user')
        </ul>
    </div>
</div>

@auth
    <div class="navbar navbar-dark navbar-expand fixed-bottom bg-primary shadow-sm d-md-none px-2">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="{{ route('links.create') }}" class="nav-link pe-3">
                    <span class="d-none d-sm-inline me-2">@lang('link.add')</span>
                    <span class="d-sm-none">
                        <x-icon.plus class="fw"/>
                        <span class="visually-hidden">@lang('link.add')</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('links.index') }}" class="nav-link pe-3">
                    <span class="d-none d-sm-inline me-2">@lang('link.all_links')</span>
                    <span class="d-sm-none">
                        <x-icon.link class="fw"/>
                        <span class="visually-hidden">@lang('link.all_links')</span>
                    </span>
                </a>
            </li>
            <li class="nav-item dropup">
                <a class="nav-link dropdown-toggle pe-3" href="#" id="navbar-lists-dd" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-none d-sm-inline me-2">@lang('list.lists')</span>
                    <span class="d-sm-none me-1">
                        <x-icon.list class="fw"/>
                        <span class="visually-hidden">@lang('list.lists')</span>
                    </span>
                    <x-icon.caret-down class="fw"/>
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
            <li class="nav-item dropup">
                <a class="nav-link dropdown-toggle pe-3" href="#" id="navbar-tags-dd" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-none d-sm-inline me-2">@lang('tag.tags')</span>
                    <span class="d-sm-none me-1">
                        <x-icon.tags class="fw"/>
                        <span class="visually-hidden">@lang('tag.tags')</span>
                    </span>
                    <x-icon.caret-down class="fw"/>
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

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a href="{{ route('get-search') }}" class="nav-link pe-3" title="@lang('search.search')">
                    <span class="d-none d-sm-inline">@lang('search.search')</span>
                    <span class="d-sm-none">
                        <x-icon.search class="fw"/>
                        <span class="visually-hidden">@lang('search.search')</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('get-trash') }}" class="nav-link" title="@lang('trash.trash')">
                    <span class="d-none d-sm-inline">@lang('trash.trash')</span>
                    <span class="d-sm-none">
                        <x-icon.trash class="fw"/>
                        <span class="visually-hidden">@lang('trash.trash')</span>
                    </span>
                </a>
            </li>
        </ul>
    </div>
@endauth

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
