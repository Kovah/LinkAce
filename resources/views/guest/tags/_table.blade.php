<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>@lang('tag.name')</th>
            <th>@lang('link.links')</th>
            <th>@lang('user.user')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tags as $tag)
            @include('guest.tags._single')
        @endforeach
        </tbody>
    </table>
</div>
