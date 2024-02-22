<div class="link-display-toggles">
    <a href="{{ request()->fullUrlWithQuery(['list-type' => Link::DISPLAY_LIST_SIMPLE]) }}"
        class="{{ usersettings('link_display_mode') ===  Link::DISPLAY_LIST_SIMPLE ? 'active' : ''}}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Display Links as simple List">
        <x-icon.list-simple/>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['list-type' => Link::DISPLAY_LIST_DETAILED]) }}"
        class="ms-1 {{ usersettings('link_display_mode') ===  Link::DISPLAY_LIST_DETAILED ? 'active' : ''}}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Display Links as detailed List">
        <x-icon.list-detailed/>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['list-type' => Link::DISPLAY_CARDS]) }}"
        class="ms-1 {{ usersettings('link_display_mode') ===  Link::DISPLAY_CARDS ? 'active' : ''}}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Display Links as Cards">
        <x-icon.list-cards/>
    </a>
</div>
