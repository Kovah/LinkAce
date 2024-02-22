@extends('layouts.app')

@section('content')

    @include('admin.system-settings.partials.updates')

    @include('admin.system-settings.partials.cron')

    @include('admin.system-settings.partials.general-settings')

    @include('admin.system-settings.partials.guest-settings')

@endsection
