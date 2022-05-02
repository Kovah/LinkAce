<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemSettingsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'system_page_title' => [
                'max:256',
            ],
            'system_guest_access' => [
                'boolean',
            ],
            'system_custom_header_content' => [
                'nullable',
                'string',
            ],
            'guest_listitem_count' => [
                'integer',
            ],
            'guest_link_display_mode' => [
                'integer',
            ],
            'guest_links_new_tab' => [
                'boolean',
            ],
            'guest_darkmode_setting' => [
                'integer',
            ],
            'guest_share' => [
                'array',
            ],
        ];
    }
}
