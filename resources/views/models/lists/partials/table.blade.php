<div class="table-responsive">
    <table class="table mb-0">
        <thead>
        <tr>
            <th>
                {!! tableSorter(trans('list.name'), $route, 'name', $orderBy, $orderDir) !!}
            </th>
            <th>
                {!! tableSorter(trans('link.links'), $route, 'links_count', $orderBy, $orderDir) !!}
            </th>
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
