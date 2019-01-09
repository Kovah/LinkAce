@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            @lang('link.add_quick')
        </div>
        <div class="card-body">

            <form action="{{ route('links.store') }}" method="POST">
                @csrf

                <input type="hidden" name="is_private" value="{{ usersettings('private_default') }}">

                <div class="input-group">
                    <input type="text" id="url" name="url" required
                        class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                        placeholder="@lang('link.url')" value="{{ old('url') }}"
                        aria-label="@lang('link.url')">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            @lang('linkace.add')
                        </button>
                    </div>
                </div>

                @if ($errors->has('url'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('url') }}
                    </p>
                @endif

            </form>

        </div>
    </div>

    <div class="row mt-4">
        <div class="col">

            <div class="card">
                <div class="card-header">
                    @lang('link.recent_links')
                </div>

                <ul class="list-group list-group-flush">
                    @forelse($recent_links as $link)
                        <a href="{{ route('links.show', [$link->id]) }}" class="list-group-item list-group-item-action">
                            {{ $link->title }}
                        </a>
                    @empty
                        <li class="list-group-item text-danger">
                            @lang('linkace.no_results_found', ['model' => trans('link.links')])
                        </li>
                    @endforelse
                </ul>
            </div>

        </div>
        <div class="col">

        </div>
    </div>

@endsection
