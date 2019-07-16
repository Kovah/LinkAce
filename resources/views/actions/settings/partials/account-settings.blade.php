<div class="card mt-4">
    <div class="card-header">
        @lang('settings.account_settings')
    </div>
    <div class="card-body">

        <form action="{{ route('save-settings-account') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="username">
                    @lang('user.username')
                </label>
                <input type="text" name="name" id="name" required
                    class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                    placeholder="@lang('user.username')" value="{{ old('name') ?: $user->name }}">
                @if ($errors->has('name'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('name') }}
                    </p>
                @endif
            </div>

            <div class="form-group">
                <label for="email">
                    @lang('user.email')
                </label>
                <input type="text" name="email" id="email" required
                    @if(env('APP_DEMO')) disabled @endif
                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    placeholder="@lang('user.email')" value="{{ old('email') ?: $user->email }}">
                @if ($errors->has('email'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i> @lang('settings.save_settings')
            </button>

        </form>

    </div>
</div>
