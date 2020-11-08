@extends('layouts.app')

@section('content')

    @include('actions.settings.partials.system.updates')

    @include('actions.settings.partials.system.cron')

    @include('actions.settings.partials.system.general-settings')

    @include('actions.settings.partials.system.guest-settings')

@endsection
