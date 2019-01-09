<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserSettingsUpdateRequest extends FormRequest
{
    /** @var bool */
    private $validate_username = false;

    /** @var bool */
    private $validate_email = false;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        // Validate the username if it was changed
        if ($request->get('username') !== auth()->user()->name) {
            $this->validate_username = true;
        }

        // Validate the email address if it was changed
        if ($request->get('email') !== auth()->user()->email) {
            $this->validate_email = true;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'timezone' => 'required',
        ];

        if ($this->validate_username) {
            $rules['username'] = 'unique:users,name';
        }

        if ($this->validate_email) {
            $rules['email'] = 'unique:users,email';
        }

        return $rules;
    }
}
