@extends('layouts.guest')

@section('content')

    <div class="link">
        @include('guest.links.partials.show.link-details')
        @include('guest.links.partials.show.link-taxonomy')
    </div>

@endsection
