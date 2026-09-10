<?php

namespace Modules\Clinic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClinicServiceImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'mimes:xlsx,xls,csv'],
        ];
    }

    #[Override]
    public function attributes()
    {
        return [
            'file' => __('clinic::attribute.file'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'file.required' => __('clinic::message.file_required'),
            'file.mimes' => __('clinic::message.file_mimes'),
        ];
    }
}
