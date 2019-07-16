@if(!env('APP_DEMO', false))
    <div class="card mt-4">
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
                    <input type="password" name="old_password" id="old_password" required
                        class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}">
                </div>

                <div class="form-group">
                    <label for="new_password">
                        @lang('settings.new_password')
                    </label>
                    <input type="password" name="new_password" id="new_password" required
                        class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}"
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">
                        @lang('settings.new_password2')
                    </label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                        class="form-control{{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key mr-2"></i> @lang('settings.change_password')
                </button>

            </form>

        </div>
    </div>
@endif

