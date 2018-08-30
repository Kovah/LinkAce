<table class="table">
    <thead>
    <tr>
        <th>@lang('category.name')</th>
        <th>@lang('category.description')</th>
        <th class="has-text-right">@lang('link.links')</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($categories as $category)
        @include('models.categories._single', ['category' => $category])
        @if($category->childCategories)
            @foreach($category->childCategories as $child_category)
                @include('models.categories._single', ['category' => $child_category, 'is_child' => true])
            @endforeach
        @endif
    @endforeach
    </tbody>
</table>
