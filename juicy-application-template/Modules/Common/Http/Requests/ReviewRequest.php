<?php

namespace Modules\Common\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'name' => 'nullable|string|max:255',
            'phone' => 'required|string',
            'food_quality' => 'required|integer|between:1,5',
            'service_speed' => 'required|integer|between:1,5',
            'staff' => 'required|integer|between:1,5',
            'cleanliness' => 'required|integer|between:1,5',
            'will_revisit' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ];
    }
}
