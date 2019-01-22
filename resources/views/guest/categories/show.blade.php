@extends('layouts.guest')

@section('content')

    <div class="card">
        <header class="card-header d-flex align-items-center">
            <span class="mr-3">
                @lang('category.category')
            </span>
            <div class="ml-auto">
                @lang('category.author', ['user' => $category->user->name])
            </div>
        </header>
        <div class="card-body">

            <div class="d-flex align-items-center">
                @if($category->parentCategory)
                    <p class="mr-2 mb-0">
                        <a href="{{ route('guest.categories.show', [$category->parentCategory->id]) }}"
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

                @if(!$category->childCategories->isEmpty())
                    <div class="col mt-3">
                        <label>@lang('category.categories')</label>
                        <br>
                        @foreach($category->childCategories as $category)
                            <a href="{{ route('guest.categories.show', [$category->id]) }}" class="badge badge-light">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            @lang('link.links')
        </div>
        <div class="card-table">

            @include('guest.links.partials.table', ['links' => $category_links])

        </div>
    </div>

    {!! $category_links->links() !!}

@endsection
