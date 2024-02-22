@props(['model'])
<div {{ $attributes->merge(['class' => 'd-inline']) }}>
    <span class="text-muted text-condensed">{{ $model->user->name }}&sol;</span>{{ $model->name }}
</div>
