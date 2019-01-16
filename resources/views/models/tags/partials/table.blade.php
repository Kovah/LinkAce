<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>@lang('tag.name')</th>
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
