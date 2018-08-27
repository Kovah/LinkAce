@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <p class="card-header-title">
                @lang('link.add_quick')
            </p>
        </div>
        <div class="card-content">

            <form action="{{ route('links.store') }}" method="POST">
                @csrf

                <input type="hidden" name="is_private" value="0">

                <div class="field">
                    <div class="field has-addons">
                        <div class="control is-expanded">
                            <input name="url" id="url" class="input{{ $errors->has('url') ? ' is-danger' : '' }}"
                                type="url" placeholder="@lang('link.url')" value="{{ old('url') }}"
                                required>
                        </div>
                        <div class="control">
                            <button type="submit" class="button is-primary">
                                @lang('linkace.add')
                            </button>
                        </div>
                    </div>

                    @if ($errors->has('url'))
                        <p class="help has-text-danger" role="alert">
                            {{ $errors->first('url') }}
                        </p>
                    @endif

                </div>

            </form>

        </div>
    </div>

@endsection
