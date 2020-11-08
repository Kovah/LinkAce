<button  type="button" id="list-index-order-dd"
    class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split"
    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    @lang('search.order_by') <x-icon.caret-down class="ml-1"/>
</button>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="list-index-order-dd">
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'created_at', 'orderDir' => 'asc']) }}">
        @lang('search.order_by.created_at:asc')
    </a>
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'created_at', 'orderDir' => 'desc']) }}">
        @lang('search.order_by.created_at:desc')
    </a>
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'name', 'orderDir' => 'asc']) }}">
        @lang('search.order_by.title:asc')
    </a>
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'name', 'orderDir' => 'desc']) }}">
        @lang('search.order_by.title:desc')
    </a>
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'links_count', 'orderDir' => 'asc']) }}">
        @lang('search.order_by.number_links:asc')
    </a>
    <a class="dropdown-item small"
        href="{{ route($baseRoute, ['orderBy' => 'links_count', 'orderDir' => 'desc']) }}">
        @lang('search.order_by.number_links:desc')
    </a>
</div>
