<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ auth()->guest() ? route('front') : route('dashboard') }}">
            <img src="{{ asset('assets/img/logo_linkace.svg') }}" alt="@lang('linkace.linkace')"
                width="81" height="30">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-content"
            aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar-content">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('guest.links.index') }}">
                        @lang('link.links')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('guest.categories.index') }}">
                        @lang('category.categories')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('guest.tags.index') }}">
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
    </div>
</nav>
