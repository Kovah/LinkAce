<div class="card">
    <div class="card-header p-2">
        <label class="label mb-0" for="note">@lang('note.add')</label>
    </div>
    <div class="card-body p-2">

        <form action="{{ route('notes.store') }}" method="post">
            @csrf

            <input type="hidden" name="link_id" value="{{ $link->id }}">

            <div class="mb-2">
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

                <x-forms.visibility-toggle class="ms-auto me-3 d-flex align-items-center" input-classes="form-select-sm"
                    label-classes="mb-0 me-2 small"/>

                <button type="submit" class="btn btn-sm btn-primary">
                    <x-icon.save class="me-2"/> @lang('note.add')
                </button>

            </div>
        </form>

    </div>
</div>
