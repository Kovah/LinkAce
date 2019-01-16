<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>@lang('category.name')</th>
            <th>@lang('category.description')</th>
            <th class="text-right">@lang('link.links')</th>
            <th>@lang('user.user')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)

            @include('guest.categories.partials.single', ['category' => $category])

            @if($category->childCategories)
                @foreach($category->childCategories as $child_category)
                    @include('guest.categories.partials.single', ['category' => $child_category, 'is_child' => true])
                @endforeach
            @endif
        @endforeach
        </tbody>
    </table>
</div>
