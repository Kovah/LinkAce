@extends('layouts.errors')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __($exception->getMessage() ?: 'Page Expired'))
