<a href="{!! $href !!}" class="link-sharing {{ $class }}" title="{{ $title }}"
    target="_blank" rel="noreferrer nofollow">
    <x-dynamic-component :component="$icon" class="fw" />
    <span class="visually-hidden">{{ $title }}</span>
</a>
