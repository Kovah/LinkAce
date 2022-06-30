@extends('layouts.app')

@section('content')

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
                            @if($user->isBlocked())
                                <span class="badge bg-warning">@lang('linkace.blocked')</span>
                            @endif
                            @if($user->trashed())
                                <span class="badge bg-danger">@lang('linkace.deleted')</span>
                            @endif
                        </div>
                        <div @class(['d-none' => $user->id === auth()->id()])>
                            @if($user->isBlocked())
                                <button type="submit" form="unblock-user-{{ $user->id }}"
                                    class="btn btn-sm btn-outline-warning">
                                    @lang('linkace.unblock')
                                </button>
                            @else
                                <button type="submit" form="block-user-{{ $user->id }}"
                                    class="btn btn-sm btn-outline-warning">
                                    @lang('linkace.block')
                                </button>
                            @endif
                            @if($user->trashed())
                                <button type="submit" form="restore-user-{{ $user->id }}"
                                    class="btn btn-sm btn-outline-danger">
                                    @lang('linkace.restore')
                                </button>
                            @else
                                <button type="submit" form="delete-user-{{ $user->id }}"
                                    class="btn btn-sm btn-outline-danger">
                                    @lang('linkace.delete')
                                </button>
                            @endif
                            <form action="{{ route('user-management-block', ['user' => $user]) }}"
                                id="block-user-{{ $user->id }}"
                                method="post" class="d-none" data-confirmation="@lang('user.block_confirmation')">
                                @csrf
                                @method('PATCH')
                            </form>
                            <form action="{{ route('user-management-unblock', ['user' => $user]) }}"
                                id="unblock-user-{{ $user->id }}"
                                method="post" class="d-none" data-confirmation="@lang('user.unblock_confirmation')">
                                @csrf
                                @method('PATCH')
                            </form>
                            <form action="{{ route('user-management-delete', ['user' => $user]) }}"
                                id="delete-user-{{ $user->id }}"
                                method="post" class="d-none" data-confirmation="@lang('user.delete_confirmation')">
                                @csrf
                                @method('DELETE')
                            </form>
                            <form action="{{ route('user-management-restore', ['user' => $user]) }}"
                                id="restore-user-{{ $user->id }}"
                                method="post" class="d-none" data-confirmation="@lang('user.restore_confirmation')">
                                @csrf
                                @method('PATCH')
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $users->withQueryString()->onEachSide(1)->links() }}

        </div>
    </div>

@endsection
