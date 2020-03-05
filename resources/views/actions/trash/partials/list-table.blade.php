<div class="table-responsive">
    <table class="table table-sm mb-0">
        <thead>
        <tr>
            <th>@lang('list.name')</th>
            <th>@lang('linkace.added_at')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach($lists as $list)
            <tr>
                <td>
                    {{ $list->name }}
                </td>
                <td class="text-muted">
                    <small>{{ formatDateTime($list->created_at) }}</small>
                </td>
                <td class="text-right">
                    <a href="{{ route('trash-restore', ['list', $list->id]) }}"
                        class="btn btn-sm btn-outline-primary" title="@lang('trash.restore')">
                        <i class="fas fa-reply"></i>
                    </a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
