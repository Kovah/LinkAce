<div class="table-responsive">
    <table class="table mb-0">
        <thead>
        <tr>
            <th>
                {!! tableSorter(trans('tag.name'), $route, 'name', $order_by, $order_dir) !!}
            </th>
            <th>@lang('link.links')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($tags as $tag)
            @include('models.tags.partials.single')
        @endforeach
        </tbody>
    </table>
</div>
