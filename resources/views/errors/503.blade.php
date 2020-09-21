@extends('layouts.errors')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('This service is currently not available. If you are the administrator, consult the application logs for details.'))
