@extends('layouts.app')

@section('content')

    <h2>Hello {{ auth()->user()->name }}</h2>

@endsection
