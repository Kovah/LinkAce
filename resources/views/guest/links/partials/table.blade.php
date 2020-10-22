<div class="table-responsive">
    <table class="table mb-0">
        <thead>
        <tr>
            <th>
                {!! tableSorter(trans('link.link'), $route, 'title', $orderBy, $orderDir) !!}
            </th>
            <th>
                {!! tableSorter(trans('link.url'), $route, 'url', $orderBy, $orderDir) !!}
            </th>
            <th>
                {!! tableSorter(trans('linkace.added_at'), $route, 'created_at', $orderBy, $orderDir) !!}
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
