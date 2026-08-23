<?php

namespace Modules\Import\DTO;

class ImportProductDto
{
    public $title;
    public $description;
    public $allergens;
    public $branch_id;
    public $category_id;
    public $price;
    public $discounted_price;
    public $sort_order;
    public $is_active;
    public $is_spicy;
    public $is_vegetarian;
    public $calories;
    public $sizes;

    public function __construct(array $node, int $branchId, int $categoryId)
    {
        $this->title = [
            'ar' => $node['title']['ar'],
            'en' => $node['title']['en'],
        ];
        $this->description = $this->locale($node['description'] ?? null);
        $this->allergens = $this->locale($node['allergens'] ?? null);
        $this->branch_id = $branchId;
        $this->category_id = $categoryId;
        $this->price = $node['price'];
        $this->discounted_price = $node['discounted_price'] ?? null;
        $this->sort_order = $node['sort_order'] ?? 1;
        $this->is_active = isset($node['is_active']) ? (int) $node['is_active'] : 1;
        $this->is_spicy = isset($node['is_spicy']) ? (int) $node['is_spicy'] : 0;
        $this->is_vegetarian = isset($node['is_vegetarian']) ? (int) $node['is_vegetarian'] : 0;
        $this->calories = $node['calories'] ?? null;

        if (!empty($node['sizes']) && is_array($node['sizes'])) {
            $this->sizes = $node['sizes'];
        }
    }

    public function dataFromImport()
    {
        $data = json_decode(json_encode($this), true);

        if (($data['sizes'] ?? null) === null) {
            unset($data['sizes']);
        }

        return $data;
    }

    private function locale($value)
    {
        if (!is_array($value)) {
            return ['ar' => '', 'en' => ''];
        }

        return [
            'ar' => $value['ar'] ?? '',
            'en' => $value['en'] ?? '',
        ];
    }
}
