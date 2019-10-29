<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>
                {!! tableSorter(trans('list.name'), $route, 'name', $order_by, $order_dir) !!}
            </th>
            <th>
                {!! tableSorter(trans('list.description'), $route, 'description', $order_by, $order_dir) !!}
            </th>
            <th class="text-right">
                @lang('link.links')
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($lists as $list)
            @include('guest.lists.partials.single', ['list' => $list])
        @endforeach
        </tbody>
    </table>
</div>
