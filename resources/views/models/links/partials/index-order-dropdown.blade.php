<button  type="button" id="link-index-order-dd"
    class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split"
    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    @lang('search.order_by') <x-icon.caret-down class="ml-1"/>
</button>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="link-index-order-dd">
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'created_at', 'orderDir' => 'asc']) }}">
        @lang('search.order_by.created_at:asc')
    </a>
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'created_at', 'orderDir' => 'desc']) }}">
        @lang('search.order_by.created_at:desc')
    </a>
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'url', 'orderDir' => 'asc']) }}">
        @lang('search.order_by.url:asc')
    </a>
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'url', 'orderDir' => 'desc']) }}">
        @lang('search.order_by.url:desc')
    </a>
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'title', 'orderDir' => 'asc']) }}">
        @lang('search.order_by.title:asc')
    </a>
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'title', 'orderDir' => 'desc']) }}">
        @lang('search.order_by.title:desc')
    </a>
</div>
