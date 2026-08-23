<?php


namespace Modules\Category\Service;


use Illuminate\Support\Facades\File;
use Modules\Branch\Entities\Branch;
use Modules\Category\Entities\Category;
use Modules\Common\Helper\UploaderHelper;

class CategoryService
{
    use UploaderHelper;

    function findAll($data = [], $relation = [])
    {
        $categories = Category::with($relation)->available();
        return getCaseCollection($categories, $data);
    }

    function active()
    {
        return Category::active()->available()->orderBy('sort_order')->get();
    }

    function main()
    {
        return Category::query()->has('products')->available()->main()->orderBy('sort_order')->get();
    }

    function mainWithoutProducts()
    {
        return Category::query()->available()->main()->get();
    }

    function allWithChilds()
    {
        return Category::with(['childrenRecursive' => function ($query) {
            $query->orderBy('sort_order');
        }])->available()->main()->orderBy('sort_order')->get();
    }

    function filter($data)
    {
        $categories = Category::active();
        if ($data['query'] ?? null) {
            $categories = $categories->whereTranslationLike('title', '%' . $data['query'] . '%');
        }
        return $categories->get();
    }

    // function bestSellerCategories($data)
    // {
    //     $products_ids = (new ProductService())->bestSellerProducts($data)->pluck('id');
    //     $categories = Category::query()->whereHas('products', function ($query) use ($products_ids) {
    //         $query->whereIn('id', $products_ids);
    //     })->get();
    //     return $categories;
    // }

    function findById($id, $relations = [])
    {
        return Category::with($relations)->findOrFail($id);
    }

    function findBy($key, $value, $relation = [])
    {
        return Category::query()->has('products')->where($key, $value)->with($relation)->get();
    }

    public function mainCategoriesByBranchId($branch_id)
    {
        return Category::main()->whereBranchId($branch_id)->orderBy('sort_order')->get();
    }

    function findByWithoutProducts($key, $value, $relation = [])
    {
        return Category::query()->available()->where($key, $value)->with($relation)->get();
    }

    function MainCategory()
    {
        return Category::where('category_id', null)->available()->where('is_active', 1)->get();
    }


    function findCategory($key, $value)
    {
        return Category::where($key, $value)->available()->first()->category_id;
    }

    function save($data)
    {
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imageName = $this->upload($image, 'category', false, true);
            $data['image'] = $imageName;
        }
        $category = Category::create($data);
        return $category;
    }

    function update($id, $data)
    {
        $Category = $this->findById($id);
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/category/' . $this->getImageName('category', $Category->image)));
            $image = request()->file('image');
            $imageName = $this->upload($image, 'category', false, true);
            $data['image'] = $imageName;
        }
        $Category->update($data);
        return $Category;
    }

    function activate($id)
    {
        $Category = $this->findById($id);
        $Category->is_active = !$Category->is_active;
        $Category->save();
    }

    function delete($id)
    {
        $Category = $this->findById($id);
        File::delete(public_path('uploads/category/' . $this->getImageName('category', $Category->image)));
        $Category->delete();
    }

    function branchCategoriesHasProducts($branch_id)
    {
        $branch = Branch::findOrFail($branch_id);
        if (!$branch->is_active) {
            return collect();
        }
        return Category::active()
            ->whereNull('category_id')
            ->with(['childrenRecursive' => function ($query) use ($branch_id) {
                $query->where('is_active', 1)
                    ->whereHas('products', function ($productQuery) use ($branch_id) {
                        $productQuery->where('products.is_active', 1)
                            ->where('products.branch_id', $branch_id);
                    });
            }])
            ->where(function ($query) use ($branch_id) {
                $query->whereHas('products', function ($productQuery) use ($branch_id) {
                    $productQuery->where('products.is_active', 1)
                        ->where('products.branch_id', $branch_id);
                })
                    ->orWhereHas('childrenRecursive', function ($childQuery) use ($branch_id) {
                        $childQuery->where('is_active', 1)
                            ->whereHas('products', function ($productQuery) use ($branch_id) {
                                $productQuery->where('products.is_active', 1)
                                    ->where('products.branch_id', $branch_id);
                            });
                    });
            })
            ->orderBy('sort_order')
            ->get();
    }

    function reorder($sort_order): void
    {
        $data = [];
        foreach ($sort_order as $index => $id) {
            $data[$index] = [
                'id' => $id,
                'sort_order' => $index + 1
            ];
        }
        Category::upsert($data, ['id'], ['sort_order']);
    }
}
