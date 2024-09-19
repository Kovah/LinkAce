<div class="card">
    <div class="card-header">
        @lang('admin.user_management.title')
    </div>
    <div class="card-body p-0">

        <div class="list-group list-group-flush">
            @foreach($users as $user)
                <div class="list-group-item d-md-flex justify-content-between">
                    <div>
                        {{ $user->name }} <span class="ms-2 text-muted">{{ $user->email }}</span>
                        @if($user->isSsoUser())
                            <span class="badge bg-secondary">@lang('auth.sso')</span>
                        @endif
                        @if($user->isBlocked())
                            <span class="badge bg-warning">@lang('linkace.blocked')</span>
                        @endif
                        @if($user->trashed())
                            <span class="badge bg-danger">@lang('linkace.deleted')</span>
                        @endif
                    </div>
                    <div class="mt-1 mt-md-0">
                        <a href="{{ route('system.users.show', ['user' => $user]) }}"
                            class="btn btn-sm btn-outline-primary">@lang('linkace.show')</a>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $users->withQueryString()->onEachSide(1)->links() }}

    </div>
</div>
