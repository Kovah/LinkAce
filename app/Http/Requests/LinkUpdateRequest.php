<?php

namespace App\Http\Requests;

use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class LinkUpdateRequest extends FormRequest
{
    /** @var Link */
    private $link;

    /** @var bool */
    private $unique_validation = false;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        // Check if the link ID was provided
        if (!$request->get('link_id')) {
            return false;
        }

        $this->link = Link::find($request->get('link_id'));

        // Check if the link belongs to the user
        if ($this->link->user_id !== auth()->user()->id) {
            return false;
        }

        // Enable unique validation if the url was changed
        Log::debug(json_encode([$this->link->url, $request->get('url')]));

        if ($this->link->url !== $request->get('url')) {
            $this->unique_validation = true;
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
        if (!$this->unique_validation) {
            return [
                'url' => 'required',
                'is_private' => 'required|boolean',
            ];
        }

        return [
            'url' => [
                'required',
                Rule::unique('links')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                }),
            ],
            'is_private' => 'required|boolean',
        ];
    }
}
