<?php

namespace App\Http\Requests\Admin;

use App\Enums\Role;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InviteUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'email',
                'unique:users,email',
                Rule::unique('user_invitations', 'email')->where(function (Builder $query) {
                    return $query->whereDate('valid_until', '>', now());
                }),
            ],
        ];
    }

    public function authorize(Request $request): bool
    {
        return $request->user()->hasRole(Role::ADMIN);
    }
}
