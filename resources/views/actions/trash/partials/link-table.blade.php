<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>@lang('link.url')</th>
            <th>@lang('linkace.added_at')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach($links as $link)
            <tr>
                <td>
                    {{ $link->url }}
                </td>
                <td class="text-muted">
                    <small>{{ formatDateTime($link->created_at) }}</small>
                </td>
                <td class="text-right">
                    <a href="{{ route('trash-restore', ['link', $link->id]) }}"
                        class="btn btn-sm btn-outline-primary" title="@lang('trash.restore')">
                        <i class="fas fa-reply"></i>
                    </a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
