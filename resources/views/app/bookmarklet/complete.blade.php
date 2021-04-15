@extends('layouts.bookmarklet')

@section('content')

    <div class="mt-3">
        <p>@lang('linkace.bookmarklet_close')</p>

        <a href="{{ route('front') }}" target="_blank" class="btn btn-primary">
            <x-icon.linkace-icon class="mr-2"/>
            @lang('linkace.open_linkace')
        </a>
    </div>

@endsection
