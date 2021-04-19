<div class="navbar navbar-dark brand-only bg-primary shadow-sm d-block d-md-none text-center">
    <a class="navbar-brand d-inline-block" href="{{ route('front') }}">
        <x-icon.linkace/>
    </a>
</div>
<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
    <div class="container px-0 px-lg-3">
        <a class="navbar-brand d-none d-md-inline-block" href="{{ route('front') }}">
            <x-icon.linkace/>
        </a>

        <ul class="navbar-nav flex-row">
            <li class="nav-item">
                <a href="{{ route('guest.links.index') }}" class="nav-link">
                    @lang('link.links')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('guest.lists.index') }}" class="nav-link pl-3 pl-md-2">
                    @lang('list.lists')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('guest.tags.index') }}" class="nav-link pl-3 pl-md-2">
                    @lang('tag.tags')
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">
                    @lang('linkace.login')
                </a>
            </li>
        </ul>
    </div>
</nav>
