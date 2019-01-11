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
                <input type="text" name="username" id="username" required
                    class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                    placeholder="@lang('user.username')" value="{{ old('username') ?: $user->name }}">
                @if ($errors->has('username'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('username') }}
                    </p>
                @endif
            </div>

            <div class="form-group">
                <label for="email">
                    @lang('user.email')
                </label>
                <input type="text" name="email" id="email" required
                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    placeholder="@lang('user.email')" value="{{ old('email') ?: $user->email }}">
                @if ($errors->has('email'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save fa-mr"></i> @lang('settings.save_settings')
            </button>

        </form>

    </div>
</div>
