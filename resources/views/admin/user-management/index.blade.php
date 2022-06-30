@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('admin.user_management.title')
        </div>
        <div class="card-body">

            <div class="d-grid row-cols-1 gap-2">
                @foreach($users as $user)
                    <div @class(['text-danger' => $user->trashed()])>
                        {{ $user->name }} <span class="ms-2 text-muted">{{ $user->email }}</span>
                    </div>
                @endforeach
            </div>

            {{ $users->withQueryString()->onEachSide(1)->links() }}

        </div>
    </div>

@endsection
