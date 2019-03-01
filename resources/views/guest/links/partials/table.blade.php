<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>
                {!! tableSorter(trans('link.link'), $route, 'title', $order_by, $order_dir) !!}
            </th>
            <th>
                {!! tableSorter(trans('link.url'), $route, 'url', $order_by, $order_dir) !!}
            </th>
            <th>
                {!! tableSorter(trans('linkace.added_at'), $route, 'created_at', $order_by, $order_dir) !!}
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($links as $link)
            @include('guest.links.partials.single-table')
        @endforeach
        </tbody>
    </table>
</div>
