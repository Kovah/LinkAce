<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>@lang('link.link')</th>
            <th>@lang('link.url')</th>
            <th>@lang('linkace.added_at')</th>
            <th>@lang('user.user')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($links as $link)
            @include('guest.links._single-table')
        @endforeach
        </tbody>
    </table>
</div>
