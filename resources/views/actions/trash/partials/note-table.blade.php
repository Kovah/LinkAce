<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover mb-0">
        <thead>
        <tr>
            <th>@lang('link.link')</th>
            <th>@lang('note.note_content')</th>
            <th>@lang('linkace.added_at')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach($notes as $note)
            <tr>
                <td>
                    <a href="{{ $note->link->url }}" title="{{ $note->link->title }}" target="_blank">
                        {{ $note->link->title }}
                    </a>
                </td>
                <td>
                    {{ $note->note }}
                </td>
                <td class="text-muted">
                    <small>{{ formatDateTime($note->created_at) }}</small>
                </td>
                <td class="text-right">
                    <a href="{{ route('trash-restore', ['note', $note->id]) }}"
                        class="btn btn-sm btn-outline-primary" title="@lang('trash.restore')">
                        <i class="fas fa-reply"></i>
                    </a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
