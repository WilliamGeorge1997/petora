<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OldBranchCategoryProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => [
                'ar' => $this->getTranslation('title', 'ar'),
                'en' => $this->getTranslation('title', 'en'),
            ],
            'description' => [
                'ar' => $this->getTranslation('description', 'ar'),
                'en' => $this->getTranslation('description', 'en'),
            ],
            'price' => $this->branch_price,
            'is_active' => $this->is_active,
            'category' => [
                'id' => $this->category->id,
                'title' => [
                    'ar' => $this->category->getTranslation('title', 'ar'),
                    'en' => $this->category->getTranslation('title', 'en'),
                ],
                'image' => $this->category->image,
            ],
            'images' => $this->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image' => $image->image,
                ];
            }),
            'attributes' => $this->getBranchAttributes(),
            'addons' => $this->getBranchAddons(),
            'created_at' => $this->created_at->format('Y-m-d H:i A'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i A'),
        ];
    }

    private function getBranchAttributes()
    {

        if (!isset($this->branch_attributes) || $this->branch_attributes->isEmpty()) {
            return [];
        }

        return $this->branch_attributes
            ->groupBy('product_attribute_id')
            ->map(function ($values, $attributeId) {
                $attribute = $values->first()->productAttribute;

                return [
                    'id' => $attribute->id,
                    'title' => [
                        'ar' => $attribute->getTranslation('title', 'ar'),
                        'en' => $attribute->getTranslation('title', 'en'),
                    ],
                    'required' => $attribute->required,
                    'multi_select' => $attribute->multi_select,
                    'override_price' => $attribute->override_price,
                    'values' => $values->map(function ($branchValue) {
                        return [
                            'id' => $branchValue->productAttributeValue->id,
                            'title' => [
                                'ar' => $branchValue->productAttributeValue->getTranslation('attribute_value', 'ar'),
                                'en' => $branchValue->productAttributeValue->getTranslation('attribute_value', 'en'),
                            ],
                            'price' => $branchValue->price,
                        ];
                    }),
                ];
            })->values();
    }

    private function getBranchAddons()
    {

        if (!isset($this->branch_addons) || $this->branch_addons->isEmpty()) {
            return [];
        }

        return $this->branch_addons
            ->groupBy('addon_id')
            ->map(function ($values, $addonId) {
                $addon = $values->first()->addon;

                return [
                    'id' => $addon->id,
                    'title' => [
                        'ar' => $addon->getTranslation('title', 'ar'),
                        'en' => $addon->getTranslation('title', 'en'),
                    ],
                    'values' => $values->map(function ($branchValue) {
                        return [
                            'id' => $branchValue->addonValue->id,
                            'title' => [
                                'ar' => $branchValue->addonValue->getTranslation('title', 'ar'),
                                'en' => $branchValue->addonValue->getTranslation('title', 'en'),
                            ],
                            'image' => $branchValue->addonValue->image,
                            'price' => $branchValue->price,
                        ];
                    }),
                ];
            })->values();
    }
}
