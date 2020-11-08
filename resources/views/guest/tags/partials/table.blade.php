<div class="table-responsive">
    <table class="table mb-0">
        <thead>
        <tr>
            <th>
                {!! tableSorter(trans('tag.name'), $route, 'name', $orderBy, $orderDir) !!}
            </th>
            <th>
                {!! tableSorter(trans('link.links'), $route, 'links_count', $orderBy, $orderDir) !!}
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($tags as $tag)
            @include('guest.tags.partials.single')
        @endforeach
        </tbody>
    </table>
</div>
