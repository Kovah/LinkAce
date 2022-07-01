@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    @lang('user.edit')
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', ['user' => $user]) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label class="form-label" for="email">@lang('user.email')</label>
                            <input type="email" name="email" id="email" class="form-control" required
                                value="{{ old('email', $user->email) }}">
                            @if ($errors->has('name'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="name">@lang('user.username')</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required placeholder="@lang('user.username')">
                            @if ($errors->has('name'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                @lang('linkace.update')
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
