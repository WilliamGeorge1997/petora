<?php

namespace Modules\Product\DTO;

class ProductDto
{
    public $title;
    public $description;
    public $is_active;
    public $price;
    public $discounted_price;
    public $category_id;
    public $addons;
    public $sides;
    public $types;
    public $sort_order;
    public $sizes;
    public $has_size;
    public $required;
    public $multi_select;
    public $override_price;
    public $attribute_id;
    public $branch_id;
    public $is_spicy;
    public $is_vegetarian;
    public $calories;
    public $allergens;
    public $multi_select_sides;
    public function __construct($request)
    {
        $admin = auth()->guard('admin')->user();
        $this->title = ['en' => $request->get('title_en'), 'ar' => $request->get('title_ar')];
        $this->description = ['en' => $request->get('description_en'), 'ar' => $request->get('description_ar')];
        $this->price = $request->get('price');
        $this->discounted_price = $request->get('discounted_price');
        $this->category_id = $request->get('category_id');
        $this->is_active = isset($request['is_active']) ? 1 : 0;
        $this->addons = $request->get('addons');
        $this->sides = $request->get('sides');
        $this->types = $request->get('types');
        $this->sort_order = $request->get('sort_order');
        // Sizes
        $this->sizes = $request->get('sizes');
        $this->has_size = $request->get('has_size');
        $this->required = $request->get('required');
        $this->multi_select = $request->get('multi_select');
        $this->override_price = $request->get('override_price');
        $this->attribute_id = $request->get('attribute_id');
        $this->branch_id = $admin->hasRole('Super Admin') ? $request->get('branch_id') : $admin->branch_id;
        $this->is_spicy = isset($request['is_spicy']) ? 1 : 0;
        $this->is_vegetarian = isset($request['is_vegetarian']) ? 1 : 0;
        $this->calories = $request->get('calories');
        $this->allergens = ['en' => $request->get('allergens_en'), 'ar' => $request->get('allergens_ar')];
        $this->multi_select_sides = isset($request['multi_select_sides']) ? 1 : 0;
    }

    public function dataFromRequest(): array
    {
        $data = json_decode(json_encode($this), true);
        if ($data['addons'] == null) unset($data['addons']);
        if ($data['sides'] == null) unset($data['sides']);
        if ($data['types'] == null) unset($data['types']);
        if ($data['sort_order'] == null) unset($data['sort_order']);
        if ($data['sizes'] == null) unset($data['sizes']);
        if ($data['has_size'] == null) unset($data['has_size']);
        if ($data['required'] == null) unset($data['required']);
        if ($data['multi_select'] == null) unset($data['multi_select']);
        if ($data['override_price'] == null) unset($data['override_price']);
        if ($data['attribute_id'] == null) unset($data['attribute_id']);
        if ($data['discounted_price'] == null) unset($data['discounted_price']);
        return $data;
    }
}
