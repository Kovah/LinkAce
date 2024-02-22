@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header d-flex">
            <div class="me-2">@lang('user.user')</div>
            <div class="ms-auto">
                <a href="{{ route('system.users.edit', ['user' => $user]) }}" class="btn btn-sm btn-primary">
                    @lang('user.edit')
                </a>
            </div>
        </div>
        <div class="card-body">
            <h2>{{ $user->name }}</h2>
            <div>{{ $user->email }}</div>
            <div class="mt-3 small text-muted">@lang('linkace.created_at') {{ $user->created_at }}</div>
        </div>
        <div class="card-footer">

            @if($user->isBlocked())
                <button type="submit" form="unblock-user-{{ $user->id }}" @disabled($user->isCurrentlyLoggedIn())
                class="btn btn-sm btn-outline-warning">
                    @lang('linkace.unblock')
                </button>
            @else
                <button type="submit" form="block-user-{{ $user->id }}" @disabled($user->isCurrentlyLoggedIn())
                class="btn btn-sm btn-outline-warning">
                    @lang('linkace.block')
                </button>
            @endif
            @if($user->trashed())
                <button type="submit" form="restore-user-{{ $user->id }}" @disabled($user->isCurrentlyLoggedIn())
                class="btn btn-sm btn-outline-danger">
                    @lang('linkace.restore')
                </button>
            @else
                <button type="submit" form="delete-user-{{ $user->id }}" @disabled($user->isCurrentlyLoggedIn())
                class="btn btn-sm btn-outline-danger">
                    @lang('linkace.delete')
                </button>
            @endif
            <form action="{{ route('system.users.block', ['user' => $user]) }}"
                id="block-user-{{ $user->id }}"
                method="post" class="d-none" data-confirmation="@lang('user.block_confirmation')">
                @csrf
                @method('PATCH')
            </form>
            <form action="{{ route('system.users.unblock', ['user' => $user]) }}"
                id="unblock-user-{{ $user->id }}"
                method="post" class="d-none" data-confirmation="@lang('user.unblock_confirmation')">
                @csrf
                @method('PATCH')
            </form>
            <form action="{{ route('system.users.delete', ['user' => $user]) }}"
                id="delete-user-{{ $user->id }}"
                method="post" class="d-none" data-confirmation="@lang('user.delete_confirmation')">
                @csrf
                @method('DELETE')
            </form>
            <form action="{{ route('system.users.restore', ['user' => $user]) }}"
                id="restore-user-{{ $user->id }}"
                method="post" class="d-none" data-confirmation="@lang('user.restore_confirmation')">
                @csrf
                @method('PATCH')
            </form>
        </div>
    </div>

@endsection
