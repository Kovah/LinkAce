@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('link.links')
            </p>
            <a href="{{ route('links.create') }}" class="card-header-icon" aria-label="@lang('link.add')">
                <div class="icon">
                    <i class="fa fa-plus fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.add')
            </a>
        </header>
        <div class="card-content">

            @if(!$links->isEmpty())

                <table class="table">
                    <thead>
                    <tr>
                        <th>@lang('link.url')</th>
                        <th>@lang('link.title')</th>
                        <th>@lang('linkace.added_at')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($links as $link)
                        <tr>
                            <td>
                                <a href="{{ $link->url }}" target="_blank">{{ $link->url }}</a>
                            </td>
                            <td>
                                {{ $link->title }}
                            </td>
                            <td>
                                {{ $link->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td>
                                <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                                    title=" @lang('link.delete')" class="button is-small is-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
                                    action="{{ route('links.destroy', [$link->id]) }}">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="link_id" value="{{ $link->id }}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            @else

                <div class="message is-warning">
                    <div class="message-body">
                        @lang('linkace.no_results_found', ['model' => trans('link.links')])
                    </div>
                </div>

            @endif

        </div>
    </div>

@endsection
