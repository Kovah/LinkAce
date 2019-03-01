<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>
                {!! tableSorter(trans('tag.name'), $route, 'name', $order_by, $order_dir) !!}
            </th>
            <th>
                @lang('link.links')
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
