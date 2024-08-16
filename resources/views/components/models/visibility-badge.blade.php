@props(['model', 'iconClass' => ''])
@if($model->visibility !== \App\Enums\ModelAttribute::VISIBILITY_PUBLIC)
    <div {{ $attributes->merge(['class' => 'visibility']) }}>
        @if($model->visibility === \App\Enums\ModelAttribute::VISIBILITY_PRIVATE)
            <x-icon.lock title="@lang($model->langBase . '.private')" :class="$iconClass"/>
            <span class="visually-hidden">@lang($model->langBase . '.private')</span>
        @elseif($model->visibility === \App\Enums\ModelAttribute::VISIBILITY_INTERNAL)
            <x-icon.shield title="@lang($model->langBase . '.internal')" :class="$iconClass"/>
            <span class="visually-hidden">@lang($model->langBase . '.internal')</span>
        @endif
    </div>
@endif
