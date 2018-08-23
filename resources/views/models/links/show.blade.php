@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('link.link')
            </p>
            <a href="{{ route('links.edit', [$link->id]) }}" class="card-header-icon" aria-label="@lang('link.edit')">
                <div class="icon">
                    <i class="fa fa-pencil fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.edit')
            </a>
        </header>
        <div class="card-content">

            <a href="{{ $link->url }}" class="is-size-3">{{ $link->title }}</a>

            <p class="has-text-grey"><a href="{{ $link->url }}">{{ $link->url }}</a></p>

            @if($link->description)
                <p>{{ $link->url }}</p>
            @endif

        </div>
    </div>

@endsection
