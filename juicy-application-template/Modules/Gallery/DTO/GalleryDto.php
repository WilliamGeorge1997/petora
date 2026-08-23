<?php

namespace Modules\Gallery\DTO;

use Illuminate\Http\Request;

class GalleryDto
{
    public $branch_id;
    public $is_active;
    public $sort_order;

    public function __construct(Request $request, bool $isUpdate = false)
    {
        $this->branch_id  = $isUpdate ? null : $this->handleBranchId($request);
        $this->sort_order = $isUpdate ? $request->input('sort_order') : null;
        $this->is_active  = $isUpdate ?  (isset($request['is_active']) ? 1 : 0) : 1;
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($data['branch_id'] == null)
            unset($data['branch_id']);
        if ($data['sort_order'] == null)
            unset($data['sort_order']);
        return $data;
    }

    private function handleBranchId($request)
    {
        /** @var \Modules\Admin\Entities\Admin $admin */
        $admin = auth()->guard('admin')->user();
        return $admin->hasRole('Branch Manager') ? $admin->branch_id : $request->input('branch_id');
    }
}
