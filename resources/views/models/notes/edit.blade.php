@extends('layouts.app')

@section('content')

    <div class="card">
        <header class="card-header">
            @lang('note.edit')
        </header>
        <div class="card-body">

            <form action="{{ route('notes.update', [$note->id]) }}" method="POST">
                @method('PATCH')
                @csrf

                <div class="mb-4">
                    <label class="form-label" class="form-label" for="note">@lang('note.note_content')</label>
                    <textarea name="note" id="note"
                        class="form-control{{ $errors->has('note') ? ' is-invalid' : '' }}"
                        title="@lang('note.note_content')" required>{{ old('note') ?: $note->note ?: '' }}</textarea>

                    @if ($errors->has('note'))
                        <p class="invalid-feedback" role="alert">
                            {{ $errors->first('note') }}
                        </p>
                    @endif
                </div>

                <div class="d-flex align-items-center">

                    <div class="form-check ms-auto me-3">
                        <input class="form-check-input" type="checkbox" id="is_private" name="is_private" value="1"
                            @if($note->is_private) checked @endif>
                        <label class="form-check-label" for="is_private">
                            <small>@lang('note.private')</small>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">
                        <x-icon.save class="me-2"/> @lang('note.edit')
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection
