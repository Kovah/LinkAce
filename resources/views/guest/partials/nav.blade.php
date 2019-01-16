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
