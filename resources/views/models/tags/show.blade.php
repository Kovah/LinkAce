@extends('layouts.app')

@section('content')

    @include('models.tags.partials.show.details')
    @include('models.tags.partials.show.links')
    @include('models.tags.partials.show.history')

@endsection
