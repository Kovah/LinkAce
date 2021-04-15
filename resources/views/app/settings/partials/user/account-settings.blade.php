<div class="card mt-5">
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
                    placeholder="@lang('placeholder.username')" value="{{ old('name') ?: $user->name }}">
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
                    placeholder="@lang('placeholder.email')" value="{{ old('email') ?: $user->email }}">
                @if ($errors->has('email'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">
                <x-icon.save class="mr-2"/> @lang('settings.save_settings')
            </button>

        </form>

    </div>
</div>
