<a href="{!! $href !!}" class="{{ $class }}" title="{{ $title }}" target="_blank" rel="noreferrer nofollow">
    <x-dynamic-component :component="$icon" class="fw" />
    <span class="visually-hidden">{{ $title }}</span>
</a>
