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
            @lang('auth.api_tokens_system')
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover">
                    <tr>
                        <th>@lang('auth.api_tokens.name')</th>
                        <th>@lang('linkace.created_at')</th>
                        <th>@lang('linkace.last_used')</th>
                        <th></th>
                    </tr>
                    @forelse($tokens as $token)
                        <tr>
                            <td>{{ $token->name }}</td>
                            <td>{{ $token->created_at }}</td>
                            <td>{{ $token->last_used ?: trans('linkace.never_used') }}</td>
                            <td>
                                <form action="{{ route('api-tokens.destroy', ['api_token' => $token]) }}" method="post"
                                    data-confirmation="@lang('auth.api_tokens.revoke_confirm')" class="text-end">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('system.api-tokens.show', ['api_token' => $token]) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        @lang('linkace.details')
                                    </a>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        @lang('auth.api_tokens.revoke')
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">@lang('auth.api_tokens.no_tokens_found')</td>
                        </tr>
                    @endforelse
                </table>
            </div>

        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            @lang('auth.api_tokens.generate')
        </div>
        <div class="card-body">

            <p class="mb-4">@lang('auth.api_tokens.generate_help_system')</p>

            <form action="{{ route('system.api-tokens.store') }}" method="post">
                @csrf
                <div class="mb-4">
                    <label class="form-label" for="token_name">
                        @lang('auth.api_tokens.name')
                    </label>
                    <input type="text" name="token_name" id="token_name" required
                        class="form-control{{ $errors->has('token_name') ? ' is-invalid' : '' }}"
                        value="{{ old('token_name') }}">
                    <p class="text-muted small mt-1">@lang('auth.api_tokens.name_help')</p>
                    @if ($errors->has('token_name'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('token_name') }}
                        </p>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label" for="private_access">
                        <input type="checkbox" id="private_access" name="private_access" class="form-check-input me-2"
                            @checked(old('private_access'))> @lang('auth.api_tokens.private_access')
                    </label>
                    <p class="text-muted small mt-1">@lang('auth.api_tokens.private_access_help')</p>
                    @if($errors->has('private_access'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('private_access') }}
                        </p>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label" for="abilities">
                        @lang('auth.api_tokens.abilities')
                    </label>
                    <select id="abilities" name="abilities[]" multiple autocomplete="off" required
                        placeholder="@lang('auth.api_tokens.abilities_select')" data-select-config='{"create":false}'
                        class="simple-select {{ $errors->has('abilities') ? ' is-invalid' : '' }}">
                        <option value="0">@lang('auth.api_tokens.abilities_select')</option>
                        @foreach(\App\Enums\ApiToken::$systemTokenAbilities as $ability)
                            <option value="{{ $ability }}">{{ $ability }}</option>
                        @endforeach
                    </select>
                    <p class="text-muted small mt-1">@lang('auth.api_tokens.abilities_help')</p>
                    @if($errors->has('private_access'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('private_access') }}
                        </p>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">@lang('auth.api_tokens.generate_short')</button>
            </form>

        </div>
    </div>

@endsection
