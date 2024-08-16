@extends('layouts.app')

@section('content')

    <div class="link">
        @include('models.links.partials.show.link-details')
        @include('models.links.partials.show.link-taxonomy')
        @include('models.links.partials.show.link-notes')
        @include('models.links.partials.show.link-history')
    </div>

@endsection
