@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                @lang('category.category')
            </p>
            <a href="{{ route('categories.edit', [$category->id]) }}" class="card-header-icon"
                aria-label="@lang('category.edit')">
                <div class="icon">
                    <i class="fa fa-pencil fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.edit')
            </a>
            <a onclick="event.preventDefault();document.getElementById('category-delete-{{ $category->id }}').submit();"
                class="card-header-icon has-text-danger" aria-label="@lang('category.delete')">
                <div class="icon">
                    <i class="fa fa-trash fa-mr" aria-hidden="true"></i>
                </div>
                @lang('linkace.delete')
            </a>
            <form id="category-delete-{{ $category->id }}" method="POST" style="display: none;"
                action="{{ route('categories.destroy', [$category->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">
            </form>
        </header>
        <div class="card-content">

            <div class="is-flex align-items-center">
                @if($category->parentCategory)
                    <small class="mr">
                        <a href="{{ route('categories.show', [$category->parentCategory->id]) }}"
                            class="tag is-primary">
                            {{ $category->parentCategory->name }} &leftarrow;
                        </a>
                    </small>
                @endif
                <h2 class="is-size-3">{{ $category->name }}</h2>
            </div>

            <br>

            <div class="columns">
                @if($category->description)
                    <div class="column">
                        {{ $category->description }}
                    </div>
                @endif

                <div class="column">
                    @if(!$category->childCategories->isEmpty())
                        <div class="field">
                            <label>@lang('category.categories')</label>
                            <br>
                            @foreach($category->childCategories as $category)
                                <a href="{{ route('categories.show', [$category->id]) }}" class="tag is-primary">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <br>

    <div class="card">
        <div class="card-header">
            <p class="card-header-title">
                @lang('link.links')
            </p>
        </div>
        <div class="card-content">
            @include('models.links._table', ['links' => $category_links])
        </div>
        @include('partials.card-pagination', ['$paginator' => $category_links])
    </div>

@endsection
