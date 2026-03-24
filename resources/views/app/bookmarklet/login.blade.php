@extends('layouts.bookmarklet')

@section('content')
    @if(databaseLoginEnabled())
        @include('auth.login-form')
    @endif
    @if(ssoLoginEnabled())
        @include('auth.oauth')
    @endif
@endsection
