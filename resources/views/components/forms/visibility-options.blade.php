@props(['setting'])
<option value="{{ \App\Enums\ModelAttribute::VISIBILITY_PUBLIC }}"
    @selected($setting === \App\Enums\ModelAttribute::VISIBILITY_PUBLIC)>
    @lang('attributes.visibility.1')
</option>
<option value="{{ \App\Enums\ModelAttribute::VISIBILITY_INTERNAL }}"
    @selected($setting === \App\Enums\ModelAttribute::VISIBILITY_INTERNAL)>
    @lang('attributes.visibility.2')
</option>
<option value="{{ \App\Enums\ModelAttribute::VISIBILITY_PRIVATE }}"
    @selected($setting === \App\Enums\ModelAttribute::VISIBILITY_PRIVATE)>
    @lang('attributes.visibility.3')
</option>
