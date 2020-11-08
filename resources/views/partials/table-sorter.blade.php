<div class="d-flex">
    <span class="mr-1">{{ $label }}</span>
    <span class="table-sorter ml-auto">
        <a href="{{ $url }}">
            <x-dynamic-component :component="$icon"/>
        </a>
    </span>
</div>
