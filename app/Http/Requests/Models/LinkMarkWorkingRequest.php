<?php

namespace App\Http\Requests\Models;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LinkMarkWorkingRequest extends FormRequest
{
    public function authorize(Request $request): bool
    {
        return $request->user()->can('update', $request->link);
    }

    public function rules(): array
    {
        return [];
    }
}
