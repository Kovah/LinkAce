@extends('layouts.app')

@section('content')

    @include('app.settings.partials.system.updates')

    @include('app.settings.partials.system.cron')

    @include('app.settings.partials.system.general-settings')

    @if(systemsettings('system_guest_access'))
        @include('app.settings.partials.system.guest-settings')
    @endif

@endsection
