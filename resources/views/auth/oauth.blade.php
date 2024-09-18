<div class="card {{ config('auth.sso.regular_login_disabled') ? '' : 'mt-4' }}">
    <div class="card-body">
        <h2 class="h6">@lang('linkace.login_with')</h2>
        <div class="d-flex flex-wrap gap-2">
            @foreach(config('auth.sso.providers') as $provider)
                @if(config('services.'.$provider.'.enabled') === true)
                    <a href="{{ route('auth.sso.redirect', ['provider' => $provider]) }}" class="btn btn-outline-primary">
                        <x-dynamic-component :component="'icon.brand.'.$provider" class="me-1"/> @lang('auth.sso.'.$provider)
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
