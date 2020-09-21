@extends('layouts.errors')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('An internal server error occured. If you are the administrator, consult the application logs for details.'))
