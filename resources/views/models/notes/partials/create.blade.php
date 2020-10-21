<div class="card">
    <div class="card-header p-2">
        <label class="label mb-0" for="note">@lang('note.add')</label>
    </div>
    <div class="card-body p-2">

        <form action="{{ route('notes.store') }}" method="post">
            @csrf

            <input type="hidden" name="link_id" value="{{ $link->id }}">

            <div class="form-group mb-2">
                <textarea name="note" id="note"
                    class="form-control{{ $errors->has('note') ? ' is-invalid' : '' }}"
                    title="@lang('note.note_content')" required>{{ old('note') ?: '' }}</textarea>

                @if ($errors->has('note'))
                    <p class="invalid-feedback" role="alert">
                        {{ $errors->first('note') }}
                    </p>
                @endif
            </div>

            <div class="d-flex align-items-center">

                <div class="custom-control custom-checkbox ml-auto mr-3">
                    <input class="custom-control-input" type="checkbox" id="is_private" name="is_private" value="1"
                        @if($link->is_private || usersettings('notes_private_default')) checked @endif>
                    <label class="custom-control-label" for="is_private">
                        <small>@lang('note.private')</small>
                    </label>
                </div>

                <button type="submit" class="btn btn-sm btn-primary">
                    <x-icon.save class="mr-2"/> @lang('note.add')
                </button>

            </div>
        </form>

    </div>
</div>
