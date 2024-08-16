@props(['model'])
@if($model->user->trashed())
    <span {{ $attributes->merge(['title' => trans('user.is_deleted'), 'class' => 'author']) }}>{{ $model->user->name }}</span>
@else
    <a href="{{ route('users.show', ['user' => $model->user]) }}" {{ $attributes->merge(['class' => 'author']) }}>{{ $model->user->name }}</a>
@endif
