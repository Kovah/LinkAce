<div class="table-responsive">
    <table class="table table-sm mb-0">
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
                    <form action="{{ route('trash-restore') }}" method="post">
                        @csrf
                        <input type="hidden" name="model" value="note">
                        <input type="hidden" name="id" value="{{ $note->id }}">
                        <button type="submit" class="btn btn-sm btn-outline-primary" title="@lang('trash.restore')">
                            <x-icon.reply/>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
