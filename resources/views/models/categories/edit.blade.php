@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('category.add')
        </header>
        <div class="card-body">

            <form action="{{ route('categories.update', [$category->id]) }}" method="POST">
                @method('PATCH')
                @csrf

                <input type="hidden" name="category_id" value="{{ $category->id }}">

                <div class="form-group">
                    <label for="name">@lang('category.name')</label>

                    <input name="name" id="name"
                        class="form-control form-control-lg{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        type="text" placeholder="@lang('category.name')" value="{{ old('name') ?: $category->name }}"
                        required autofocus>

                    @if ($errors->has('name'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>

                <br>

                <div class="row">
                    <div class="col">

                        <div class="form-group">
                            <label for="description">
                                @lang('category.description')
                            </label>

                            <textarea name="description" id="description" rows="4" class="form-control"
                                placeholder="@lang('category.description')">{{ old('description') ?: $category->description }}</textarea>

                            @if ($errors->has('description'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                        </div>

                    </div>
                    <div class="col">

                        <div class="form-group">
                            <label for="parent_category">
                                @lang('category.parent_category')
                            </label>

                            <select id="parent_category" name="parent_category"
                                class="{{ $errors->has('parent_category') ? ' is-invalid' : '' }}">
                                <option value="0">@lang('category.select_parent_category')</option>
                                @foreach($categories as $select_category)
                                    <option value="{{ $select_category->id }}"
                                        @if(old('parent_category')
                                            || (old('parent_category') === null && $category->parent_category === $select_category->id)
                                        ) selected @endif>
                                        {{ $select_category->name }}
                                    </option>
                                @endforeach
                            </select>


                            @if ($errors->has('parent_category'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('parent_category') }}
                                </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="is_private">
                                @lang('linkace.is_private')
                            </label>

                            <select id="is_private" name="is_private"
                                class="form-control{{ $errors->has('parent_category') ? ' is-invalid' : '' }}">
                                <option value="0" @if($category->is_private === 0) selected @endif>
                                    @lang('linkace.no')
                                </option>
                                <option value="1" @if($category->is_private === 1) selected @endif>
                                    @lang('linkace.yes')
                                </option>
                            </select>

                            @if ($errors->has('is_private'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('is_private') }}
                                </p>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="mt-3 text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> @lang('category.edit')
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $('#parent_category').selectize({
            create: false
        });
    </script>
@endpush
