<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class HashtagRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('hashtag')?->id;
        return [
            'text' => ['required', 'string', 'unique:hashtags,text,' . $id],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    public function attributes()
    {
        return [

        ];
    }

    #[Override]
    public function messages()
    {
        return [

        ];
    }
}