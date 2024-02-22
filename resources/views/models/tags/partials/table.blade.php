<div class="bulk-edit table-responsive" data-type="tags">
    <table class="table mb-0">
        <thead>
        <tr>
            <th>
                {!! tableSorter(trans('tag.name'), $route, 'name', $orderBy, $orderDir) !!}
            </th>
            <th>
                {!! tableSorter(trans('link.links'), $route, 'links_count', $orderBy, $orderDir) !!}
            </th>
            <th>
                <form class="bulk-edit-form visually-hidden text-end" action="{{ route('bulk-edit.form') }}" method="POST">
                    @csrf()
                    <input type="hidden" name="type">
                    <input type="hidden" name="models">
                    <div class="btn-group mt-1">
                        <button type="button" class="bulk-edit-submit btn btn-xs btn-outline-primary">Edit</button>
                        <button type="button" class="bulk-edit-select-all btn btn-xs btn-outline-primary">Select all</button>
                    </div>
                </form>
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($tags as $tag)
            @include('models.tags.partials.single', ['tag' => $tag])
        @endforeach
        </tbody>
    </table>
</div>
