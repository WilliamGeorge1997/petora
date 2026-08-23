<?php

namespace Modules\Import\DTO;

class ImportCategoryDto
{
    public $title;
    public $branch_id;
    public $category_id;
    public $sort_order;
    public $is_active;

    public function __construct(array $node, int $branchId, ?int $parentId = null)
    {
        $this->title = [
            'ar' => $node['title']['ar'],
            'en' => $node['title']['en'],
        ];
        $this->branch_id = $branchId;
        $this->category_id = $parentId;
        $this->sort_order = $node['sort_order'] ?? 1;
        $this->is_active = isset($node['is_active']) ? (int) $node['is_active'] : 1;
    }

    public function dataFromImport()
    {
        return json_decode(json_encode($this), true);
    }
}
