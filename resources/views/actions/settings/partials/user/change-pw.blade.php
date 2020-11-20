@if(!env('APP_DEMO', false))
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
                    <input type="password" name="current_password" id="current_password" required
                        class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}">
                </div>

                <div class="form-group">
                    <label for="new_password">
                        @lang('settings.new_password')
                    </label>
                    <input type="password" name="password" id="password" required
                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">
                        @lang('settings.new_password2')
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    <x-icon.key class="mr-2"/> @lang('settings.change_password')
                </button>

            </form>

        </div>
    </div>
@endif

