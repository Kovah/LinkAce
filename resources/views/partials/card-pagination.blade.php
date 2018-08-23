@if ($paginator->hasPages())
    <div class="card-footer" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <div class="card-footer-item is-disabled" aria-disabled="true"
                aria-label="@lang('pagination.previous')">
                <span aria-hidden="true">&lsaquo;</span>
            </div>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="card-footer-item"
                aria-label="@lang('pagination.previous')">
                &lsaquo;
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <div class="card-footer-item is-disabled" aria-disabled="true"><span>{{ $element }}</span></div>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <div class="card-footer-item is-active" aria-current="page">
                            <span>{{ $page }}</span>
                        </div>
                    @else
                        <a href="{{ $url }}" class="card-footer-item">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="card-footer-item" rel="next"
                aria-label="@lang('pagination.next')">
                &rsaquo;
            </a>
        @else
            <div class="card-footer-item is-disabled" aria-disabled="true"
                aria-label="@lang('pagination.next')">
                <span aria-hidden="true">&rsaquo;</span>
            </div>
        @endif
    </div>
@endif

