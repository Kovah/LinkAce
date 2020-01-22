<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>
                {!! tableSorter(trans('list.name'), $route, 'name', $order_by, $order_dir) !!}
            </th>
            <th>@lang('link.links')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($lists as $list)
            @include('models.lists.partials.single')
        @endforeach
        </tbody>
    </table>
</div>
