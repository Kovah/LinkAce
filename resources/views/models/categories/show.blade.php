@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">
            <span class="mr-3">
                @lang('category.category')
            </span>
            <div class="ml-auto">
                <a href="{{ route('categories.edit', [$category->id]) }}" class="btn btn-sm btn-primary"
                    aria-label="@lang('category.edit')">
                    <i class="fa fa-pencil fa-mr"></i>
                    @lang('linkace.edit')
                </a>
                <a onclick="event.preventDefault();document.getElementById('category-delete-{{ $category->id }}').submit();"
                    class="btn btn-sm btn-outline-danger" aria-label="@lang('category.delete')">
                    <i class="fa fa-trash fa-mr"></i>
                    @lang('linkace.delete')
                </a>
            </div>
            <form id="category-delete-{{ $category->id }}" method="POST" style="display: none;"
                action="{{ route('categories.destroy', [$category->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">
            </form>
        </header>
        <div class="card-body">

            <div class="d-flex align-items-center">
                @if($category->parentCategory)
                    <p class="mr-2 mb-0">
                        <a href="{{ route('categories.show', [$category->parentCategory->id]) }}"
                            class="badge badge-primary">
                            {{ $category->parentCategory->name }} &leftarrow;
                        </a>
                    </p>
                @endif
                <h2 class="mb-0">{{ $category->name }}</h2>
            </div>

            <div class="row">
                @if($category->description)
                    <div class="col mt-3">
                        {{ $category->description }}
                    </div>
                @endif

                <div class="col mt-3">
                    @if(!$category->childCategories->isEmpty())
                        <label>@lang('category.categories')</label>
                        <br>
                        @foreach($category->childCategories as $category)
                            <a href="{{ route('categories.show', [$category->id]) }}" class="badge badge-light">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>

    <br>

    <div class="card">
        <div class="card-header">
            @lang('link.links')
        </div>
        <div class="card-table">

            @include('models.links._table', ['links' => $category_links])

        </div>
    </div>

    {!! $category_links->links() !!}

@endsection
