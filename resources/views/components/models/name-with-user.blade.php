@props(['model'])
<div {{ $attributes->merge(['class' => 'd-inline author']) }}>
    <span class="text-muted text-condensed">{{ $model->user->name }}&sol;</span>{{ $model->name }}
</div>
