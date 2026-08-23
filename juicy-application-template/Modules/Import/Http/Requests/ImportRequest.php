<?php

namespace Modules\Import\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Category\Entities\Category;
use Modules\Import\Service\ImportService;
use Modules\Product\Entities\Addon;

class ImportRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->guard('admin')->check()
            && auth()->guard('admin')->user()->hasRole('Super Admin');
    }

    protected function prepareForValidation()
    {
        $json = trim((string) $this->input('json', ''));

        if ($json === '') {
            return;
        }

        try {
            $this->merge([
                'data' => app(ImportService::class)->parse($json),
            ]);
        } catch (\Throwable $e) {
            $this->merge([
                'json_error' => $e->getMessage(),
            ]);
        }
    }

    public function rules()
    {
        $title = 'required|string|max:191';

        return array_merge(
            [
                'branch_id' => 'required|exists:branches,id',
                'json' => 'required|string|max:512000',
                'data' => 'required|array',
                'data.categories' => 'required|array|min:1',
                'data.categories.*.title.ar' => $title,
                'data.categories.*.title.en' => $title,
                'data.categories.*.sort_order' => 'nullable|integer|min:1',
                'data.categories.*.is_active' => 'nullable|boolean',
                'data.categories.*.subcategories' => 'nullable|array',
                'data.categories.*.subcategories.*.title.ar' => $title,
                'data.categories.*.subcategories.*.title.en' => $title,
                'data.categories.*.subcategories.*.sort_order' => 'nullable|integer|min:1',
                'data.categories.*.subcategories.*.is_active' => 'nullable|boolean',
            ],
            $this->productRules('data.categories.*.products'),
            $this->productRules('data.categories.*.subcategories.*.products'),
            $this->addonRules()
        );
    }

    private function addonRules()
    {
        $title = 'required|string|max:191';

        return [
            'data.addons' => 'nullable|array',
            'data.addons.*.title.ar' => $title,
            'data.addons.*.title.en' => $title,
            'data.addons.*.sort_order' => 'nullable|integer|min:1',
            'data.addons.*.is_active' => 'nullable|boolean',
            'data.addons.*.multi_select' => 'nullable|boolean',
            'data.addons.*.values' => 'required|array|min:1',
            'data.addons.*.values.*.title.ar' => $title,
            'data.addons.*.values.*.title.en' => $title,
            'data.addons.*.values.*.price' => 'required|numeric|min:0',
            'data.addons.*.values.*.sort_order' => 'nullable|integer|min:1',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            if ($this->filled('json_error')) {
                $validator->errors()->add('json', $this->input('json_error'));

                return;
            }

            $branchId = (int) $this->input('branch_id');

            if ($branchId > 0 && Category::where('branch_id', $branchId)->exists()) {
                $validator->errors()->add(
                    'branch_id',
                    'هذا الفرع يحتوي بالفعل على أقسام. الاستيراد للقوائم الجديدة فقط (فرع فارغ).'
                );
            }

            if ($branchId > 0 && Addon::where('branch_id', $branchId)->exists()) {
                $validator->errors()->add(
                    'branch_id',
                    'هذا الفرع يحتوي بالفعل على إضافات. الاستيراد للقوائم الجديدة فقط (فرع فارغ).'
                );
            }

            $data = $this->input('data', []);

            foreach ($data['categories'] ?? [] as $i => $category) {
                foreach ($category['subcategories'] ?? [] as $j => $subcategory) {
                    if (!empty($subcategory['subcategories'])) {
                        $validator->errors()->add(
                            'json',
                            'categories[' . $i . '].subcategories[' . $j . ']: الأقسام الفرعية مستوى واحد فقط.'
                        );
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
            'branch_id.required' => 'يجب اختيار الفرع.',
            'branch_id.exists' => 'الفرع المحدد غير موجود.',
            'json.required' => 'يجب لصق JSON القائمة هنا.',
            'json.max' => 'حجم JSON كبير جداً.',
            'data.required' => 'JSON القائمة غير صالح أو فارغ.',
            'data.categories.required' => 'الحقل categories مطلوب.',
            'data.categories.min' => 'يجب إضافة قسم واحد على الأقل.',
            'data.categories.*.title.ar.required' => 'عنوان القسم بالعربية مطلوب.',
            'data.categories.*.title.en.required' => 'عنوان القسم بالإنجليزية مطلوب.',
            'data.categories.*.products.*.price.required' => 'سعر المنتج مطلوب.',
            'data.categories.*.subcategories.*.title.ar.required' => 'عنوان القسم الفرعي بالعربية مطلوب.',
        ];
    }

    private function productRules($prefix)
    {
        $title = 'required|string|max:191';
        $locale = 'nullable|string|max:191';

        return [
            $prefix => 'nullable|array',
            $prefix . '.*.title.ar' => $title,
            $prefix . '.*.title.en' => $title,
            $prefix . '.*.description.ar' => $locale,
            $prefix . '.*.description.en' => $locale,
            $prefix . '.*.allergens.ar' => $locale,
            $prefix . '.*.allergens.en' => $locale,
            $prefix . '.*.price' => 'required|numeric|min:0',
            $prefix . '.*.discounted_price' => 'nullable|numeric|min:0',
            $prefix . '.*.sort_order' => 'nullable|integer|min:1',
            $prefix . '.*.is_active' => 'nullable|boolean',
            $prefix . '.*.is_spicy' => 'nullable|boolean',
            $prefix . '.*.is_vegetarian' => 'nullable|boolean',
            $prefix . '.*.calories' => 'nullable|numeric|min:0',
            $prefix . '.*.sizes' => 'nullable|array',
            $prefix . '.*.sizes.*.name_ar' => 'required|string|max:191',
            $prefix . '.*.sizes.*.name_en' => 'required|string|max:191',
            $prefix . '.*.sizes.*.price' => 'required|numeric|min:0',
        ];
    }
}
