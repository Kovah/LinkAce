@extends('layouts.app')

@section('content')

    @include('admin.users.partials.user-list')
    @include('admin.users.partials.invitations')

@endsection
