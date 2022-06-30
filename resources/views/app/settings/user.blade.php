@extends('layouts.app')

@section('content')

    @include('app.settings.partials.bookmarklet')

    @include('app.settings.partials.api')

    @include('app.settings.partials.account-settings')

    @include('app.settings.partials.change-pw')

    @include('app.settings.partials.two-factor')

    @include('app.settings.partials.app-settings')

@endsection
