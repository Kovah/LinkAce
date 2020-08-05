@extends('layouts.app')

@section('content')

    @include('actions.settings.partials.user.bookmarklet')

    @include('actions.settings.partials.user.api')

    @include('actions.settings.partials.user.account-settings')

    @include('actions.settings.partials.user.change-pw')

    @include('actions.settings.partials.user.app-settings')

@endsection
