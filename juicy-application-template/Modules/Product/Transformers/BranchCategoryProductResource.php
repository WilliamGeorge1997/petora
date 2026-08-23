<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchCategoryProductResource extends JsonResource
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
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            // 'category' => [
            //     'id' => $this->category->id,
            //     'title' => [
            //         'ar' => $this->category->getTranslation('title', 'ar'),
            //         'en' => $this->category->getTranslation('title', 'en'),
            //     ],
            //     'image' => $this->category->image,
            // ],
            'images' => $this->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image' => $image->image,
                ];
            }),
            'calories' => @$this->calories,
            'allergens' => [
                'ar' => @$this->getTranslation('allergens', 'ar'),
                'en' => @$this->getTranslation('allergens', 'en'),
            ],
            'is_spicy' => $this->is_spicy,
            'is_vegetarian' => $this->is_vegetarian,
            'types' => @$this->types,
            'multi_select_sides' => $this->multi_select_sides,
            'attributes' => $this->handleAttributes(),
            'addons' => @$this->addons,
            'sides' => @$this->sides,
            'created_at' => $this->created_at->format('Y-m-d H:i A'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i A'),
        ];
    }

    private function handleAttributes()
    {
        return $this->attributes->map(function ($attribute) {
            return [
                "id" => $attribute->id,
                "title" => [
                    "ar" => $attribute->getTranslation('title', 'ar'),
                    "en" => $attribute->getTranslation('title', 'en'),
                ],
                "required" => $attribute->required,
                "multi_select" => $attribute->multi_select,
                "override_price" => $attribute->override_price,
                "values" => $attribute->values->map(function ($value) {
                    return [
                        "id" => $value->id,
                        "title" => [
                            "ar" => $value->getTranslation('attribute_value', 'ar'),
                            "en" => $value->getTranslation('attribute_value', 'en'),
                        ],
                        "price" => $value->price,
                        "image" => $value->image,
                    ];
                }),
            ];
        });
    }
}
