@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('list.edit')
        </header>
        <div class="card-body">

            <form action="{{ route('lists.update', [$list->id]) }}" method="POST">
                @method('PATCH')
                @csrf

                <input type="hidden" name="list_id" value="{{ $list->id }}">

                <div class="mb-4">
                    <label class="form-label" for="name">@lang('list.name')</label>

                    <input name="name" id="name"
                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        type="text" placeholder="@lang('placeholder.list_name')"
                        value="{{ old('name') ?: $list->name }}" required autofocus>

                    @if ($errors->has('name'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-7">
                        <div class="mb-4">
                            <label class="form-label" for="description">@lang('list.description')</label>

                            <textarea name="description" id="description" rows="4"
                                class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                >{{ old('description') ?: $list->description }}</textarea>

                            @if ($errors->has('description'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-5">
                        <x-forms.visibility-toggle class="mb-4" :existing-value="$list->visibility"/>
                    </div>
                </div>

                <div class="mt-3 d-sm-flex flex-wrap align-items-center">

                    <div class="d-sm-inline-block mb-3 mb-sm-0 me-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            onclick="window.deleteList.submit()">
                            <x-icon.trash class="me-2"/> @lang('list.delete')
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <x-icon.save class="me-2"/> @lang('list.update')
                    </button>

                </div>

            </form>

        </div>
    </div>

    <form action="{{ route('lists.destroy', [$list->id]) }}" method="post" id="deleteList">
        @csrf
        @method('DELETE')
    </form>

@endsection
