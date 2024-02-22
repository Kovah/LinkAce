<?php

namespace App\Http\Requests\Admin;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function rules(Request $request): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users')->ignore($request->route('user')->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->route('user')->id),
            ],
        ];
    }

    public function authorize(Request $request): bool
    {
        return $request->user()->hasRole(Role::ADMIN);
    }
}
