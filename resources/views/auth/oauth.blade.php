<div class="card {{ config('auth.oauth.regular_login_disabled') ? '' : 'mt-4' }}">
    <div class="card-body">
        <h2 class="h6">Login with</h2>
        <div class="d-flex flex-wrap gap-2">
            @foreach(config('auth.oauth.providers') as $provider)
                @if(config('services.'.$provider.'.enabled') === true)
                    <a href="{{ route('auth.oauth.redirect', ['provider' => $provider]) }}" class="btn btn-outline-primary">
                        <x-dynamic-component :component="'icon.brand.'.$provider" class="me-1"/> {{ ucwords($provider) }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
