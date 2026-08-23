<?php

namespace Modules\Product\Service;

use Modules\Product\Entities\Side;
use Modules\Product\Entities\SideValue;
use Modules\Common\Helper\UploaderHelper;

class SideService
{
    use UploaderHelper;

    function findAll($data = [], $relation = [])
    {
        $sides = Side::with($relation)->available()->orderBy('sort_order');

        return getCaseCollection($sides, $data);
    }

    function active()
    {
        return Side::active()->available()->orderBy('sort_order')->get();
    }

    public function findByBranch($branch_id)
    {
        return Side::available()->where('branch_id', $branch_id)->orderBy('sort_order')->get();
    }

    public function reorder($sort_order): void
    {
        $data = [];
        foreach ($sort_order as $index => $id) {
            $data[$index] = [
                'id'         => $id,
                'sort_order' => $index + 1,
            ];
        }
        Side::upsert($data, ['id'], ['sort_order']);
    }

    public function store($data)
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin->hasRole('Super Admin'))
            $data['branch_id'] = $admin->branch_id;
        return Side::create($data);
    }

    function update($id, $data)
    {
        $side = $this->find($id);
        return $side->update($data);
    }

    function storeValues($data)
    {
        foreach ($data as $side_value) {
            if ($side_value['image'] ?? null) {
                $image = $side_value['image'];
                $sliderImageName = $this->upload($image, 'sides');
                $side_value['image'] = $sliderImageName;
            }
            SideValue::create($side_value);
        }
    }

    function updateValues($id, $data)
    {
        $ids = array_column($data, 'id');
        SideValue::where('side_id', $id)->whereNotIn('id', $ids)->delete();
        foreach ($data as $values) {
            if ($values['image'] ?? null) {
                $image = $values['image'];
                $sliderImageName = $this->upload($image, 'sides');
                $values['image'] = $sliderImageName;
            }
            SideValue::updateOrCreate(['id' => $values['id']], $values);
        }
    }

    function find($id, $relation = [])
    {
        return Side::with($relation)->findOrFail($id);
    }


    function delete($id)
    {
        $side = $this->find($id);
        return $side->delete();
    }

    function activate($id)
    {
        $side = $this->find($id);
        $side->is_active = !$side->is_active;
        $side->save();
    }
}
