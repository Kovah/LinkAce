@extends('layouts.app')

@section('content')

    @if(session()->has('new_token'))
        <div class="alert alert-warning mb-4">
            <p class="text-xl mb-2">
                <strong>
                    @lang('auth.api_tokens.generated_successfully', ['token' => session()->get('new_token')])
                </strong>
            </p>
            <p class="mb-0">@lang('auth.api_tokens.generated_help')</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            @lang('auth.api_token_system')
        </div>
        <div class="card-body">

            <h2 class="mb-3">{{ $token->name }}</h2>

            <p>@lang('auth.api_tokens.abilities'):</p>
            <ul>
                @foreach($token->abilities as $ability)
                    <li>{{ $ability }}</li>
                @endforeach
            </ul>

            <p>@lang('auth.api_tokens.ability_private_access'): {{ in_array(\App\Enums\ApiToken::ABILITY_SYSTEM_ACCESS_PRIVATE, $token->abilities) ? trans('linkace.yes') : trans('linkace.no') }}</p>
        </div>
    </div>

@endsection
