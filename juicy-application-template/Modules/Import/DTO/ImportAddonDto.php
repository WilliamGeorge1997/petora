<?php

namespace Modules\Import\DTO;

class ImportAddonDto
{
    public $title;
    public $branch_id;
    public $multi_select;
    public $is_active;
    public $values;

    public function __construct(array $node, int $branchId)
    {
        $this->title = [
            'ar' => $node['title']['ar'],
            'en' => $node['title']['en'],
        ];
        $this->branch_id = $branchId;
        $this->multi_select = isset($node['multi_select']) ? (int) $node['multi_select'] : 1;
        $this->is_active = isset($node['is_active']) ? (int) $node['is_active'] : 1;
        $this->values = [];

        foreach ($node['values'] ?? [] as $value) {
            $this->values[] = [
                'title' => [
                    'ar' => $value['title']['ar'],
                    'en' => $value['title']['en'],
                ],
                'price' => $value['price'],
            ];
        }
    }

    public function dataFromImport()
    {
        return json_decode(json_encode($this), true);
    }
}
