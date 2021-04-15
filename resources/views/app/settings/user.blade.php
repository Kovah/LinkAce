@extends('layouts.app')

@section('content')

    @include('app.settings.partials.user.bookmarklet')

    @include('app.settings.partials.user.api')

    @include('app.settings.partials.user.account-settings')

    @include('app.settings.partials.user.change-pw')

    @include('app.settings.partials.user.two-factor')

    @include('app.settings.partials.user.app-settings')

@endsection
