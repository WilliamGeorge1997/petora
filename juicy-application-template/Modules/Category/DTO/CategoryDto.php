<?php


namespace Modules\Category\DTO;


class CategoryDto
{

    public $title;
    public $category_id;
    public $image;
    public $is_active;
    public $sort_order;
    public $branch_id;

    public function __construct($request)
    {
        $admin = auth()->guard('admin')->user();
        $this->title = ['en' => $request->get('title_en'), 'ar' => $request->get('title_ar')];
        $this->category_id = $request->get('category_id') ?? null;
        if ($request->hasFile('image')) $this->image = $request->file('image');
        $this->is_active = isset($request['is_active']) ? 1 : 0;
        $this->sort_order = $request->get('sort_order');
        $this->branch_id = $admin->hasRole('Super Admin') ? $request->get('branch_id') : $admin->branch_id;
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($data['image'] == null) unset($data['image']);
        // if ($data['category_id'] == null) unset($data['category_id']);
        if ($data['sort_order'] == null) unset($data['sort_order']);
        return $data;
    }
}
