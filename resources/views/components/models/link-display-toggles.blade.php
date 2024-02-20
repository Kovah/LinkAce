@php
$currentSetting = auth()->check() ? usersettings('link_display_mode') : session('link_display_mode');
@endphp
<div {{ $attributes->merge(['class' => 'link-display-toggles']) }}>
    <a href="{{ request()->fullUrlWithQuery(['link-display' => Link::DISPLAY_LIST_SIMPLE]) }}"
        class="{{ $currentSetting ===  Link::DISPLAY_LIST_SIMPLE ? 'active' : ''}}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('settings.display_mode_list_simple')">
        <x-icon.list-simple/>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['link-display' => Link::DISPLAY_LIST_DETAILED]) }}"
        class="ms-1 {{ in_array($currentSetting, [Link::DISPLAY_LIST_DETAILED, null])  ? 'active' : ''}}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('settings.display_mode_list_detailed')">
        <x-icon.list-detailed/>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['link-display' => Link::DISPLAY_CARDS]) }}"
        class="ms-1 {{ $currentSetting ===  Link::DISPLAY_CARDS ? 'active' : ''}}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('settings.display_mode_cards')">
        <x-icon.list-cards/>
    </a>
</div>
