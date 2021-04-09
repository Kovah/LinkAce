<div class="card card-body p-3 mb-3">

    <div class="note-content small">
        {!! $note->formatted_note !!}
    </div>
    <div class="note-meta mt-2 small d-flex align-items-center">
        <div class="ml-auto mr-2 text-muted">
            @if($note->is_private)
                <span>
                    <x-icon.lock class="mr-1" title="@lang('note.private')"/>
                    <span class="sr-only">@lang('note.private')</span>
                </span>
            @endif
            {!! $note->addedAt() !!}
        </div>
        <div class="text-right">

            <div class="btn-group">
                <a href="{{ route('notes.edit', [$note->id]) }}" class="btn btn-xs btn-outline-secondary"
                    aria-label="@lang('note.edit')">
                    <x-icon.edit class="mr-2"/>
                    @lang('linkace.edit')
                </a>
                <a onclick="event.preventDefault();document.getElementById('note-delete-{{ $note->id }}').submit();"
                    class="btn btn-xs btn-outline-danger cursor-pointer" aria-label="@lang('note.delete')">
                    <x-icon.trash class="mr-2"/>
                    @lang('linkace.delete')
                </a>
            </div>

            <form id="note-delete-{{ $note->id }}" method="POST" style="display: none;"
                action="{{ route('notes.destroy', [$note->id]) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="note_id" value="{{ $note->id }}">
            </form>

        </div>
    </div>

</div>
