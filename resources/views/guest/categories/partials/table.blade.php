<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>
                {!! tableSorter(trans('category.name'), $route, 'name', $order_by, $order_dir) !!}
            </th>
            <th>
                {!! tableSorter(trans('category.description'), $route, 'description', $order_by, $order_dir) !!}
            </th>
            <th class="text-right">
                @lang('link.links')
            </th>
            <th>
                {!! tableSorter(trans('user.user'), $route, 'user_id', $order_by, $order_dir) !!}
            </th>
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
