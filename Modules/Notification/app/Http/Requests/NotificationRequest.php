<?php

namespace Modules\Notification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class NotificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['required', 'string', 'max:255'],
            'description_en' => ['required', 'string'],
            'description_ar' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
            'notifiable_type' => ['nullable', 'string'],
            'notifiable_id' => ['nullable', 'integer'],
            'subject_type' => ['nullable', 'string'],
            'subject_id' => ['nullable', 'integer'],
            'group_by' => ['nullable', 'string', 'max:255'],
            'all_clients' => ['nullable'],
            'clients' => ['nullable', 'array'],
            'clients.*' => ['integer', 'exists:clients,id'],
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
    public function attributes(): array
    {
        return [
            'title_en' => __('notification::attribute.title_en'),
            'title_ar' => __('notification::attribute.title_ar'),
            'description_en' => __('notification::attribute.description_en'),
            'description_ar' => __('notification::attribute.description_ar'),
            'image' => __('notification::attribute.image'),
            'notifiable_type' => __('notification::attribute.notifiable_type'),
            'notifiable_id' => __('notification::attribute.notifiable_id'),
            'subject_type' => __('notification::attribute.subject_type'),
            'subject_id' => __('notification::attribute.subject_id'),
            'group_by' => __('notification::attribute.group_by'),
        ];
    }
}
