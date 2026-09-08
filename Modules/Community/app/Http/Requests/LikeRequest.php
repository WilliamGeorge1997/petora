<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Community\Models\Comment;
use Modules\Community\Models\Post;
use Override;

class LikeRequest extends FormRequest
{
    #[Override]
    protected function prepareForValidation(): void
    {
        if ($id = $this->route('post_id')) {
            $this->merge(['likeable_id' => (int) $id, 'likeable_type' => Post::class]);
        } elseif ($id = $this->route('comment_id')) {
            $this->merge(['likeable_id' => (int) $id, 'likeable_type' => Comment::class]);
        }

        $this->merge([
            'client_id' => auth('client')->id(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $table = match ($this->likeable_type) {
            Post::class    => 'posts',
            Comment::class => 'comments',
            default        => null,
        };

        return [
            'client_id'     => ['required', 'integer', 'exists:clients,id'],
            'likeable_type' => ['required', 'string'],
            'likeable_id'   => [
                'required',
                'integer',
                $table ? "exists:{$table},id" : '',
            ],
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
            'client_id' => __('community::attribute.client_id'),
            'likeable_id' => __('community::attribute.likeable_id'),
            'likeable_type' => __('community::attribute.likeable_type'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'client_id.required'     => __('community::message.client_id_required'),
            'client_id.integer'      => __('community::message.client_id_integer'),
            'client_id.exists'       => __('community::message.client_id_exists'),
            'likeable_id.required'   => __('community::message.likeable_id_required'),
            'likeable_id.exists'     => __('community::message.likeable_id_exists'),
            'likeable_type.required' => __('community::message.likeable_type_required'),
        ];
    }
}