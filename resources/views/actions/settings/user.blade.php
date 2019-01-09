@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('settings.user_settings')
        </div>
        <div class="card-body">

            <form action="{{ route('save-usersettings') }}" method="POST">
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

                <div class="form-group">
                    <label for="timezone">
                        @lang('settings.timezone')
                    </label>
                    <select id="timezone" name="timezone"
                        class="custom-select{{ $errors->has('timezone') ? ' is-invalid' : '' }}">
                        @foreach(timezone_identifiers_list() as $key => $zone)
                            <option value="{{ $zone }}" @if($user->settings()->get('timezone') === $zone) selected @endif>
                                {{ $zone }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('timezone'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('timezone') }}
                        </p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="private_default">
                        @lang('settings.private_default')
                    </label>
                    <select id="private_default" name="private_default"
                        class="custom-select{{ $errors->has('private_default') ? ' is-invalid' : '' }}">
                        <option value="0" @if($user->settings()->get('private_default') === '0') selected @endif>
                            @lang('linkace.no')
                        </option>
                        <option value="1" @if($user->settings()->get('private_default') === '1') selected @endif>
                            @lang('linkace.yes')
                        </option>
                    </select>
                    <p class="text-muted small">@lang('settings.private_default_help')</p>
                    @if ($errors->has('private_default'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('private_default') }}
                        </p>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">
                    @lang('settings.save_settings')
                </button>

            </form>

        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header">
            @lang('settings.change_password')
        </div>
        <div class="card-body">

            <form action="{{ route('change-user-password') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="old_password">
                        @lang('settings.old_password')
                    </label>
                    <input type="text" name="old_password" id="old_password" required
                        class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}"
                        placeholder="@lang('settings.old_password')">
                </div>

                <div class="form-group">
                    <label for="new_password">
                        @lang('settings.new_password')
                    </label>
                    <input type="text" name="new_password" id="new_password" required
                        class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}"
                        placeholder="@lang('settings.new_password')">
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">
                        @lang('settings.new_password2')
                    </label>
                    <input type="text" name="new_password_confirmation" id="new_password_confirmation" required
                        class="form-control{{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}"
                        placeholder="@lang('settings.new_password2')">
                </div>

                <button type="submit" class="btn btn-primary">
                    @lang('settings.change_password')
                </button>

            </form>

        </div>
    </div>

@endsection
