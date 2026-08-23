<?php

namespace Modules\Gallery\Service;

use Illuminate\Support\Facades\File;
use Modules\Common\Helper\UploaderHelper;
use Modules\Gallery\Entities\Gallery;


class GalleryService
{
    use UploaderHelper;

    public function findAll(array $data, array $relations)
    {
        $query = Gallery::query()->available()->with($relations)->orderByDesc('branch_id')->orderBy('sort_order');
        return getCaseCollection($query, $data);
    }

    public function findBy($key, $value, $data, $selections)
    {
        $query = Gallery::query()->select($selections)->active()->where($key, $value)->orderBy('sort_order');
        return getCaseCollection($query, $data);
    }

    public function findById($id): Gallery
    {
        return Gallery::available()->findOrFail($id);
    }

    public function findMaxSort($branch_id)
    {
        return Gallery::whereBranchId($branch_id)->max('sort_order');
    }

    public function findByBranch($branch_id)
    {
        return Gallery::available()->where('branch_id', $branch_id)->orderBy('sort_order')->get();
    }

    public function active(array $data, array $relations)
    {
        $query = Gallery::query()->available()->active()->with($relations)->orderBy('sort_order');
        return getCaseCollection($query, $data);
    }

    public function save(array $data)
    {
        $max_sort = $this->findMaxSort($data['branch_id']) ?? 0;
        $save_data = [];
        foreach (request()->file('images') as $image) {
            $max_sort++;
            $save_data[] = [
                'branch_id' => $data['branch_id'],
                'image' =>  $this->upload($image, 'gallery', false, false),
                'sort_order' => $max_sort,
                'is_active' => $data['is_active'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        Gallery::insert($save_data);
    }

    public function update(array $data, $id)
    {
        $gallery = $this->findById($id);
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/gallery/' . $this->getImageName('gallery', $gallery->image)));
            $data['image'] = $this->upload(request()->file('image'), 'gallery', false, false);
        }
        $gallery->update($data);
    }


    public function activate($id)
    {
        $gallery = $this->findById($id);
        $gallery->is_active = !$gallery->is_active;
        $gallery->save();
    }


    public function delete($id)
    {
        $gallery = $this->findById($id);
        File::delete(public_path('uploads/gallery/' . $this->getImageName('gallery', $gallery->image)));
        $gallery->delete();
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
        Gallery::upsert($data, ['id'], ['sort_order']);
    }
}
