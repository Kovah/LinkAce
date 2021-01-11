<a href="{!! $href !!}" class="{{ $class }}" title="{{ $title }}">
    <x-dynamic-component :component="$icon" class="fw" />
    <span class="sr-only">{{ $title }}</span>
</a>
