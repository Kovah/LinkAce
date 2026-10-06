<?php

namespace App\Http\Requests;

use App\Models\Link;
use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserSettingsUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'locale' => [
                'required',
                Rule::in(array_keys(config('app.available_locales'))),
            ],
            'timezone' => [
                'required',
                'timezone',
            ],
            'date_format' => [
                'sometimes',
                Rule::in(config('linkace.formats.date')),
            ],
            'time_format' => [
                'sometimes',
                Rule::in(config('linkace.formats.time')),
            ],
            'listitem_count' => [
                'sometimes',
                Rule::in(config('linkace.listitem_count_values')),
            ],
            'darkmode_setting' => [
                'sometimes',
                Rule::in([0, 1, 2]),
            ],
            'link_display_mode' => [
                'sometimes',
                Rule::in([Link::DISPLAY_CARDS, Link::DISPLAY_LIST_SIMPLE, Link::DISPLAY_LIST_DETAILED]),
            ],
            'links_default_visibility' => [
                'sometimes',
                'integer',
                new ModelVisibility(),
            ],
            'notes_default_visibility' => [
                'sometimes',
                'integer',
                new ModelVisibility(),
            ],
            'lists_default_visibility' => [
                'sometimes',
                'integer',
                new ModelVisibility(),
            ],
            'tags_default_visibility' => [
                'sometimes',
                'integer',
                new ModelVisibility(),
            ],
            'links_new_tab' => [
                'sometimes',
                'boolean',
            ],
            'markdown_for_text' => [
                'sometimes',
                'boolean',
            ],
            'archive_backups_enabled' => [
                'sometimes',
                'boolean',
            ],
            'archive_private_backups_enabled' => [
                'sometimes',
                'boolean',
            ],
            'share' => [
                'sometimes',
                'array',
            ],
        ];
    }
}
