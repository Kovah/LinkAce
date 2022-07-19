@props(['model'])
<div {{ $attributes->merge(['class' => 'd-inline']) }}>
    <span class="text-muted">{{ $model->user->name }}&sol;</span>{{ $model->name }}
</div>
