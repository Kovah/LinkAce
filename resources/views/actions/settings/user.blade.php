@extends('layouts.app')

@section('content')

    @include('actions.settings.partials.account-settings')

    @include('actions.settings.partials.change-pw')

    @include('actions.settings.partials.app-settings')

@endsection
