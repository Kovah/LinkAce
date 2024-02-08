@props(['setting'])
<option value="1" @selected($setting === true)>
    @lang('linkace.yes')
</option>
<option value="0" @selected($setting === false)>
    @lang('linkace.no')
</option>
