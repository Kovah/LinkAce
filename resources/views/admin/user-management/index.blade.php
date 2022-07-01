@extends('layouts.app')

@section('content')

    @include('admin.user-management.partials.user-list')
    @include('admin.user-management.partials.invitations')

@endsection
