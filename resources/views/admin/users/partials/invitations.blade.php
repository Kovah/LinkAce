<div class="card mt-4">
    <div class="card-header">
        @lang('admin.user_management.invitations')
    </div>
    <div class="card-body p-0">

        <div class="card-body">
            <p>@lang('admin.user_management.invite_help')</p>

            <form action="{{ route('users-invite') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">
                        @lang('linkace.email')
                    </label>
                    <div class="input-group mb-3">
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                        <button type="submit" class="btn btn-primary">@lang('admin.user_management.invite')</button>
                    </div>
                    @if ($errors->has('email'))
                        <p class="invalid-feedback mt-1" role="alert">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>
            </form>
        </div>

        @if($invitations->isNotEmpty())
            <hr>
            <div class="list-group list-group-flush">
                @foreach($invitations as $invite)
                    <div class="list-group-item d-md-flex justify-content-between">
                        <div @class(['text-muted' => $invite->isCompleted()])>
                            {{ $invite->email }}
                            <div class="small text-muted">
                                @if($invite->isCompleted())
                                    @lang('admin.user_management.invite_accepted_by', ['user' => $invite->createdUser->name, 'id' => $invite->createdUser->id])
                                @else
                                    @lang('admin.user_management.invite_valid_until', ['datetime' => $invite->valid_until])
                                @endif
                            </div>
                        </div>
                        <div @class(['mt-1 mt-md-0'])>
                            <button type="submit" form="delete-invite-{{ $invite->id }}"
                                class="btn btn-sm btn-outline-danger">
                                @lang('linkace.delete')
                            </button>
                            <form action="{{ route('users-invite-delete', ['invitation' => $invite]) }}"
                                id="delete-invite-{{ $invite->id }}"
                                method="post" class="d-none" data-confirmation="@lang('user.restore_confirmation')">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $invitations->withQueryString()->onEachSide(1)->links() }}
        @endif

    </div>
</div>
