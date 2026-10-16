@extends('layouts.errors')

@section('title', __('Server Error'))
@section('code', '500')
@section('message')
    {{ __('An internal server error occured. If you are the administrator, consult the application logs for details.') }}

    @php
        $sqliteFileMissing = false;
        for ($e = $exception ?? null; $e !== null; $e = $e->getPrevious()) {
            $sqliteFileMissing = $sqliteFileMissing || $e instanceof \Illuminate\Database\SQLiteDatabaseDoesNotExistException;
        }
    @endphp
    @if($sqliteFileMissing)
        <p class="mt-3 mb-0">{{ __('If you are new to LinkAce, please make sure that your SQLite database file is present.') }}</p>
    @endif
@endsection
