<?php


namespace Modules\Product\Service;


use Modules\Common\Helper\UploaderHelper;
use Modules\Product\Entities\Addon;
use Modules\Product\Entities\AddonValue;

class AddonService
{
    use UploaderHelper;

    function findAll($data = [], $relation = [])
    {
        $addons = Addon::with($relation)->available()->orderBy('sort_order');

        return getCaseCollection($addons, $data);
    }

    function active()
    {
        return Addon::active()->available()->orderBy('sort_order')->get();
    }

    public function findByBranch($branch_id)
    {
        return Addon::available()->where('branch_id', $branch_id)->orderBy('sort_order')->get();
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
        Addon::upsert($data, ['id'], ['sort_order']);
    }

    public function store($data)
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin->hasRole('Super Admin'))
            $data['branch_id'] = $admin->branch_id;
        return Addon::create($data);
    }

    function update($id, $data)
    {
        $addon = $this->find($id);
        return $addon->update($data);
    }

    function storeValues($data)
    {
        foreach ($data as $addon_value) {
            if ($addon_value['image'] ?? null) {
                $image = $addon_value['image'];
                $sliderImageName = $this->upload($image, 'addons');
                $addon_value['image'] = $sliderImageName;
            }
            AddonValue::create($addon_value);
        }
    }

    function updateValues($id, $data)
    {
        $ids = array_column($data, 'id');
        AddonValue::where('addon_id', $id)->whereNotIn('id', $ids)->delete();
        foreach ($data as $values) {
            if ($values['image'] ?? null) {
                $image = $values['image'];
                $sliderImageName = $this->upload($image, 'addons');
                $values['image'] = $sliderImageName;
            }
            AddonValue::updateOrCreate(['id' => $values['id']], $values);
        }
    }

    function find($id, $relation = [])
    {
        return Addon::with($relation)->findOrFail($id);
    }


    function delete($id)
    {
        $product_Attribute = $this->find($id);
        return $product_Attribute->delete();
    }

    function activate($id)
    {
        $addon = $this->find($id);
        $addon->is_active = !$addon->is_active;
        $addon->save();
    }

    /**
     * Create addon and values from menu import (no images / no nested transaction).
     */
    public function createFromImport(array $data): Addon
    {
        $values = $data['values'] ?? [];
        unset($data['values']);

        $addon = Addon::create($data);

        foreach ($values as $value) {
            AddonValue::create([
                'addon_id' => $addon->id,
                'title' => $value['title'],
                'price' => $value['price'],
                'image' => null,
            ]);
        }

        return $addon;
    }
}
