@props(['withoutWrapper' => false])
{!! $withoutWrapper ? '<!--' : '' !!}<div {{ $attributes->merge(['class' => 'dropdown']) }}>{!! $withoutWrapper ? '-->' : '' !!}
    <button type="button" id="link-index-order-dd"
        class="btn btn-sm btn-outline-primary dropdown-toggle dropdown-toggle-split"
        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        @lang('search.order_by')
        <x-icon.caret-down class="ml-1"/>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="link-index-order-dd">
        <a class="dropdown-item small"
            href="{{ request()->fullUrlWithQuery(['orderBy' => 'created_at', 'orderDir' => 'asc']) }}">
            @lang('search.order_by.created_at:asc')
        </a>
        <a class="dropdown-item small"
            href="{{ request()->fullUrlWithQuery(['orderBy' => 'created_at', 'orderDir' => 'desc']) }}">
            @lang('search.order_by.created_at:desc')
        </a>
        <a class="dropdown-item small"
            href="{{ request()->fullUrlWithQuery(['orderBy' => 'url', 'orderDir' => 'asc']) }}">
            @lang('search.order_by.url:asc')
        </a>
        <a class="dropdown-item small"
            href="{{ request()->fullUrlWithQuery(['orderBy' => 'url', 'orderDir' => 'desc']) }}">
            @lang('search.order_by.url:desc')
        </a>
        <a class="dropdown-item small"
            href="{{ request()->fullUrlWithQuery(['orderBy' => 'title', 'orderDir' => 'asc']) }}">
            @lang('search.order_by.title:asc')
        </a>
        <a class="dropdown-item small"
            href="{{ request()->fullUrlWithQuery(['orderBy' => 'title', 'orderDir' => 'desc']) }}">
            @lang('search.order_by.title:desc')
        </a>
        <a class="dropdown-item small"
            href="{{ request()->fullUrlWithQuery(['orderBy' => 'random']) }}">
            @lang('search.order_by.random')
        </a>
    </div>
{!! $withoutWrapper ? '<!--' : '' !!}</div>{!! $withoutWrapper ? '-->' : '' !!}
