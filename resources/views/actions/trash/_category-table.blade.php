<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>@lang('category.name')</th>
            <th>@lang('linkace.added_at')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach($categories as $category)
            <tr>
                <td>
                    {{ $category->name }}
                </td>
                <td>
                    {{ formatDateTime($category->created_at) }}
                </td>
                <td class="text-right">
                    <a href="{{ route('trash-restore', ['category', $category->id]) }}"
                        class="btn btn-sm btn-outline-primary" title="@lang('trash.restore')">
                        <i class="fa fa-reply"></i>
                    </a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
