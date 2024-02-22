@extends('layouts.app')

@section('content')

    <form action="{{ route('bulk-edit.update-lists') }}" method="POST" class="card">
        @csrf
        <input type="hidden" name="models" value="{{ old('models', implode(',', $models)) }}">
        <header class="card-header">@choice('list.bulk_title', $modelCount, ['count' => $modelCount])</header>
        <div class="card-body">
            <div class="row">
                <x-forms.visibility-toggle class="col-6" :unchanged-option="true"/>
            </div>

            <div class="mt-3 d-sm-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <x-icon.save class="me-2"/> @lang('list.update_lists')
                </button>
            </div>
        </div>
    </form>

    <form action="{{ route('bulk-edit.delete') }}" method="POST" class="card mt-4">
        @csrf
        <input type="hidden" name="type" value="links">
        <input type="hidden" name="models" value="{{ implode(',', $models) }}">
        <header class="card-header">@choice('list.delete', $modelCount)</header>
        <div class="card-body">
            <div class="text-end">
                <button type="submit" class="btn btn-danger">
                    <x-icon.save class="me-2"/> @choice('list.delete', $modelCount)
                </button>
            </div>
        </div>
    </form>

@endsection
