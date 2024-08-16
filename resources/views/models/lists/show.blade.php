@extends('layouts.app')

@section('content')

    @include('models.lists.partials.show.details')
    @include('models.lists.partials.show.links')
    @include('models.lists.partials.show.history')

@endsection
