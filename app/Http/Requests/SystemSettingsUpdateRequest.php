<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates both the general system settings and the guest settings, as both
 * are saved by the SystemSettingsController. All fields are optional because
 * each of the two forms only submits its own subset.
 */
class SystemSettingsUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // System settings
            'page_title' => [
                'max:256',
                'nullable',
                'string',
            ],
            'logo_text' => [
                'max:20',
                'nullable',
                'string',
            ],
            'additional_footer_link_url' => [
                'nullable',
                'string',
                'required_with:additional_footer_link_text'
            ],
            'additional_footer_link_text' => [
                'max:20',
                'nullable',
                'string',
                'required_with:additional_footer_link_url'
            ],
            'contact_page_enabled' => [
                'sometimes',
                'boolean',
            ],
            'contact_page_title' => [
                'max:20',
                'nullable',
                'string',
            ],
            'contact_page_content' => [
                'max:10000',
                'nullable',
                'string',
            ],
            'custom_header_content' => [
                'nullable',
                'string',
            ],

            // Guest settings
            'guest_access_enabled' => [
                'sometimes',
                'boolean',
            ],
            'locale' => [
                'sometimes',
                Rule::in(array_keys(config('app.available_locales'))),
            ],
            'listitem_count' => [
                'sometimes',
                Rule::in(config('linkace.listitem_count_values')),
            ],
            'links_new_tab' => [
                'sometimes',
                'boolean',
            ],
            'darkmode_setting' => [
                'sometimes',
                Rule::in([0, 1, 2]),
            ],
            'guest_share' => [
                'sometimes',
                'array',
            ],
        ];
    }
}
