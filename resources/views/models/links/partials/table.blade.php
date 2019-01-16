<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>@lang('link.link')</th>
            <th>@lang('link.url')</th>
            <th>@lang('linkace.added_at')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($links as $link)
            @include('models.links.partials.single-table')
        @endforeach
        </tbody>
    </table>
</div>
