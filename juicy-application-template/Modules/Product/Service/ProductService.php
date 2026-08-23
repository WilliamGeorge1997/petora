<?php

namespace Modules\Product\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Category\Entities\Category;
use Modules\Common\Helper\UploaderHelper;
use Modules\Order\Entities\OrderDetailsAttribute;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductAttributeValue;
use Modules\Product\Entities\ProductImage;
use Modules\Product\Entities\ProductType;
use Modules\Subscription\Service\SubscriptionService;

class ProductService
{
    use UploaderHelper;

    function findAll($data = [], $relation = [])
    {
        $products = Product::query()->available()
            ->when($data['title'] ?? null, function ($query) use ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('title->ar', 'like', '%' . $data['title'] . '%')
                        ->orWhere('title->en', 'like', '%' . $data['title'] . '%');
                });
            })
            ->when($data['branch_id'] ?? null, function ($query) use ($data) {
                $query->where('branch_id', $data['branch_id']);
            })
            ->when(isset($data['is_active']) && $data['is_active'] !== '', function ($query) use ($data) {
                $query->where('is_active', (int) $data['is_active']);
            })
            ->with($relation)
            ->orderByDesc('id');
        return getCaseCollection($products, $data);
    }

    function active()
    {
        return Product::query()->active()->available()->get();
    }

    function filter($data)
    {
        $products = Product::active();
        if ($data['query'] ?? null) {
            $products = $products->whereTranslationLike('title', '%' . $data['query'] . '%');
        }
        return $products->get();
    }

    function findById($id, $relation = []): Product
    {
        return Product::with($relation)->findOrFail($id);
    }

    function findBy($key, $value)
    {
        return Product::where($key, $value)->get();
    }

    public function offerProducts($branch_id)
    {
        return product::whereBranchId($branch_id)->active()->select(['id', 'title'])->get();
    }

    // Subscription product count limit
    public function getBranchProductCount(int $branchId): int
    {
        return Product::where('branch_id', $branchId)->count();
    }

    public function assertCanCreateProduct(int $branchId, int $quantity): void
    {
        $subscription = (new SubscriptionService())->activeByBranchId($branchId);
    
        if (!$subscription) {
            throw new \Exception('لا يمكن إنشاء منتجات بدون اشتراك نشط.');
        }
    
        if (is_null($subscription->product_count)) {
            return;
        }
    
        $limit = (int) $subscription->product_count;
    
        if ($this->getBranchProductCount($branchId) + $quantity > $limit) {
            throw new \Exception("لقد وصلت إلى الحد الأقصى للمنتجات المسموح بها في اشتراكك ({$limit} منتج).");
        }
    }

    function save($data)
    {
        $this->assertCanCreateProduct($data['branch_id'], 1);

        DB::beginTransaction();
        try {
            $product = Product::create($data);

            if (request()->hasFile('images')) {
                $images = request()->file('images');
                foreach ($images as $image) {
                    $imageName = $this->uploadMedia($image, 'product', false, true);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imageName
                    ]);
                }
            }

            if (isset($data['addons']) && count($data['addons']) >= 1) {
                $product->addons()->sync($data['addons']);
            }

            if (isset($data['sides']) && count($data['sides']) >= 1) {
                $product->sides()->sync($data['sides']);
            }

            if (isset($data['types']) && is_array($data['types'])) {
                $this->saveProductTypes($product->id, $data['types']);
            }

            if (isset($data['has_size']) && $data['has_size'] == 1 && isset($data['sizes']) && is_array($data['sizes']) && count($data['sizes']) != 0) {
                $this->saveProductSizes($product->id, $data);
            }

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    function createFromImport(array $data): Product
    {
        $sizes = $data['sizes'] ?? null;
        unset($data['sizes'], $data['has_size']);

        $product = Product::create($data);

        if (is_array($sizes) && count($sizes) > 0) {
            $this->saveProductSizes($product->id, [
                'sizes' => $sizes,
                'required' => 0,
                'multi_select' => 0,
                'override_price' => 0,
            ]);
        }

        return $product;
    }

    private function saveProductSizes($productId, $data = [])
    {
        $productAttribute = ProductAttribute::create([
            'product_id' => $productId,
            'title' => ['en' => 'Size', 'ar' => 'الحجم'],
            'required' => isset($data['required']) ? 1 : 0,
            'multi_select' => isset($data['multi_select']) ? 1 : 0,
            'override_price' => isset($data['override_price']) ? 1 : 0,
        ]);
        foreach ($data['sizes'] as $size) {
            ProductAttributeValue::create([
                'product_attribute_id' => $productAttribute->id,
                'attribute_value' => ['en' => $size['name_en'], 'ar' => $size['name_ar']],
                'price' => $size['price'],
            ]);
        }
    }


    function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $Product = $this->findById($id);

            if (request()->hasFile('images')) {
                $images = request()->file('images');
                foreach ($images as $image) {
                    $imageName = $this->uploadMedia($image, 'product', false, true);
                    ProductImage::create([
                        'product_id' => $id,
                        'image' => $imageName
                    ]);
                }
            }

            $Product->update($data);

            $Product->addons()->sync(@$data['addons']);

            $Product->sides()->sync(@$data['sides']);

            if (isset($data['types']) && is_array($data['types'])) {
                $this->updateProductTypes($id, $data['types']);
            } else {
                $this->deleteProductTypes($id);
            }

            if (isset($data['has_size']) && $data['has_size'] == 1 && isset($data['sizes']) && is_array($data['sizes']) && count($data['sizes']) != 0) {
                $this->updateProductSizes($Product, $data);
            } elseif (!isset($data['has_size']) || $data['has_size'] == 0) {
                $this->deleteProductSizes($id);
            }

            DB::commit();
            return $Product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function deleteProductSizes($id)
    {
        $productAttribute = ProductAttribute::where('product_id', $id)->where('title->en', 'Size')->first();
        if ($productAttribute) {
            $productAttribute->delete();
        }
    }

    private function updateProductSizes($product, $data)
    {
        $productAttribute = null;

        if (!empty($data['attribute_id'])) {
            $productAttribute = ProductAttribute::find($data['attribute_id']);
        }

        if (!$productAttribute) {
            $productAttribute = ProductAttribute::create([
                'product_id' => $product->id,
                'title' => ['en' => 'Size', 'ar' => 'الحجم'],
                'required' => isset($data['required']) ? 1 : 0,
                'multi_select' => isset($data['multi_select']) ? 1 : 0,
                'override_price' => isset($data['override_price']) ? 1 : 0,
            ]);
        } else {
            // Update attribute options when editing
            $productAttribute->update([
                'required' => isset($data['required']) ? 1 : 0,
                'multi_select' => isset($data['multi_select']) ? 1 : 0,
                'override_price' => isset($data['override_price']) ? 1 : 0,
            ]);
        }

        $sizes = $data['sizes'] ?? [];
        $ids = array_filter(array_column($sizes, 'id'));

        if (!empty($ids)) {
            $valuesToDelete = ProductAttributeValue::where('product_attribute_id', $productAttribute->id)
                ->whereNotIn('id', $ids)
                ->get();
        } else {
            $valuesToDelete = ProductAttributeValue::where('product_attribute_id', $productAttribute->id)->get();
        }

        foreach ($valuesToDelete as $value) {
            $hasOrders = OrderDetailsAttribute::where('product_attribute_value_id', $value->id)
                ->exists();

            if (!$hasOrders) {
                $value->delete();
            }
        }

        foreach ($sizes as $size) {
            if (!empty($size['id'])) {
                ProductAttributeValue::where('id', $size['id'])
                    ->update([
                        'attribute_value' => ['en' => $size['name_en'], 'ar' => $size['name_ar']],
                        'price' => $size['price'],
                    ]);
            } else {
                ProductAttributeValue::create([
                    'product_attribute_id' => $productAttribute->id,
                    'attribute_value' => ['en' => $size['name_en'], 'ar' => $size['name_ar']],
                    'price' => $size['price'],
                ]);
            }
        }
    }

    function activate($id)
    {
        $Product = $this->findById($id);
        $Product->is_active = !$Product->is_active;
        $Product->save();
    }

    function delete($id)
    {
        $Product = $this->findById($id);
        foreach ($Product->images as $row) {
            File::delete(public_path('uploads/product/' . $this->getImageName('product', $row->image)));
        }
        foreach ($Product->types as $type) {
            if ($type->image) {
                File::delete(public_path('uploads/product_types/' . $this->getImageName('product_types', $type->image)));
            }
        }
        $Product->delete();
    }

    protected function saveProductTypes(int $productId, array $types): void
    {
        foreach ($types as $index => $typeData) {
            $imageName = null;
            if (request()->hasFile("types.{$index}.image")) {
                $imageName = $this->upload(request()->file("types.{$index}.image"), 'product_types', false, true);
            }

            ProductType::create([
                'product_id' => $productId,
                'title' => [
                    'en' => $typeData['title_en'],
                    'ar' => $typeData['title_ar']
                ],
                'price' => $typeData['price'],
                'image' => $imageName,
                'sort_order' => $typeData['sort_order'] ?? $index,
                'is_active' => $typeData['is_active'] ?? 1,
            ]);
        }
    }

    protected function updateProductTypes(int $productId, array $types): void
    {
        $ids = array_filter(array_column($types, 'id'));

        $existingTypes = ProductType::where('product_id', $productId)
            ->whereNotIn('id', $ids)
            ->get();

        foreach ($existingTypes as $type) {
            if ($type->image) {
                File::delete(public_path('uploads/product_types/' . $this->getImageName('product_types', $type->image)));
            }
            $type->delete();
        }

        foreach ($types as $index => $typeData) {
            $imageName = null;

            if (request()->hasFile("types.{$index}.image")) {
                $imageName = $this->upload(request()->file("types.{$index}.image"), 'product_types', false, true);

                if (isset($typeData['id'])) {
                    $existingType = ProductType::find($typeData['id']);
                    if ($existingType && $existingType->image) {
                        File::delete(public_path('uploads/product_types/' . $this->getImageName('product_types', $existingType->image)));
                    }
                }
            } elseif (isset($typeData['id'])) {
                $existingType = ProductType::find($typeData['id']);
                if ($existingType) {
                    $imageName = $existingType->image;
                }
            }

            $productType = ProductType::updateOrCreate(
                ['id' => $typeData['id'] ?? null],
                [
                    'product_id' => $productId,
                    'title' => [
                        'en' => $typeData['title_en'],
                        'ar' => $typeData['title_ar']
                    ],
                    'price' => $typeData['price'],
                    'image' => $imageName,
                    'sort_order' => $typeData['sort_order'] ?? $index,
                    'is_active' => $typeData['is_active'] ?? 1,
                ]
            );
        }
    }

    protected function deleteProductTypes(int $productId): void
    {
        $types = ProductType::where('product_id', $productId)->get();

        foreach ($types as $type) {
            if ($type->image) {
                File::delete(public_path('uploads/product_types/' . $this->getImageName('product_types', $type->image)));
            }
            $type->delete();
        }
    }

    function attributeStore($data)
    {
        return ProductAttribute::create($data);
    }

    function attributeUpdate($id, $data)
    {
        $attribute = $this->findAttribute($id);
        return $attribute->update($data);
    }

    function attributeValuesStore($data)
    {
        foreach ($data['attribute_values'] as $index => $attribute_value) {
            if (request()->hasFile("attribute_values.$index.image"))
                $attribute_value['image'] = $this->upload(request()->file("attribute_values.$index.image"), 'attribute_value', false, true);
            ProductAttributeValue::create($attribute_value);
        }
    }

    function attributeValuesUpdate($id, $data)
    {
        $ids = array_column($data['attribute_values'], 'id');

        $toBeDeleted = ProductAttributeValue::where('product_attribute_id', $id)->whereNotIn('id', $ids)->get();

        if (OrderDetailsAttribute::whereIn('product_attribute_value_id', $toBeDeleted->pluck('id'))->exists()) {
            throw new \Exception('لا يمكن حذف بعض قيم الخاصية لأنها مستخدمة في طلبات سابقة');
        }

        foreach ($toBeDeleted as $row) {
            if ($row->image) {
                File::delete(public_path(
                    'uploads/attribute_value/' . $this->getImageName('attribute_value', $row->image)
                ));
            }
        }
        ProductAttributeValue::where('product_attribute_id', $id)->whereNotIn('id', $ids)->delete();


        foreach ($data['attribute_values'] as $index => $attribute_value) {

            if (request()->hasFile("attribute_values.$index.image")) {
                $attributeValue = !empty($attribute_value['id'])
                    ? ProductAttributeValue::find($attribute_value['id'])
                    : null;


                if ($attributeValue && $attributeValue->image) {
                    File::delete(public_path(
                        'uploads/attribute_value/' . $this->getImageName('attribute_value', $attributeValue->image)
                    ));
                }

                $attribute_value['image'] = $this->upload(request()->file("attribute_values.$index.image"), 'attribute_value', false, true);
            }

            ProductAttributeValue::updateOrCreate(['id' => $attribute_value['id']], $attribute_value);
        }
    }

    function findAttribute($id, $relation = [])
    {
        return ProductAttribute::with($relation)->findOrFail($id);
    }

    function productAttributes($id)
    {
        return ProductAttribute::whereProductId($id)->where('title->en', '!=', 'Size')->get();
    }

    function deleteProductAttributes($id)
    {
        // Check if the attribute is used in orders
        if ($this->productAttributeUsedInOrders($id))
            throw new \Exception('لا يمكن حذف هذه الخاصية لأنها مستخدمة في طلبات سابقة');


        $product_Attribute = $this->findAttribute($id);
        File::delete(public_path(
            'uploads/attribute_value/' . $this->getImageName('attribute_value', $product_Attribute->image)
        ));
        return $product_Attribute->delete();
    }


    function productAttributeUsedInOrders($id)
    {
        return OrderDetailsAttribute::where('product_attribute_id', $id)->exists();
    }

    function branchCategoryProducts($branch_id, $category_id)
    {
        $category = Category::with([
            'products' => function ($query) use ($branch_id) {
                $query
                    ->active()
                    ->where('branch_id', $branch_id)
                    ->orderBy('sort_order')
                    ->with(['images', 'addons.values', 'sides.values', 'attributes.values']);
            },
            'childs' => function ($query) use ($branch_id) {
                $query->active()->has('products')->where('branch_id', $branch_id)->orderBy('sort_order')
                    ->with([
                        'products' => function ($subQuery) use ($branch_id) {
                            $subQuery
                                ->active()
                                ->where('branch_id', $branch_id)
                                ->orderBy('sort_order')
                                ->with(['images', 'addons.values', 'sides.values', 'attributes.values']);
                        }
                    ]);
            }
        ])->findOrFail($category_id);

        return $category;
    }

    // function branchCategoryProducts($branch_id, $category_id)
    // {
    //     $childrenRecursive = Category::with(['childrenRecursive' => function ($query)  use ($branch_id) {
    //         $query->where('is_active', 1)->where('branch_id', $branch_id)->orderBy('sort_order')
    //             ->with(['products' => function ($query) use ($branch_id) {
    //                 $query->active()->orderBy('sort_order')->where('branch_id', $branch_id)
    //                     ->with(['images', 'category', 'types', 'addons.values', 'attributes.values']);
    //             }]);
    //     }])->findOrFail($category_id);

    //     $branchProducts = Product::query()
    //         ->active()
    //         ->where('branch_id', $branch_id)
    //         ->where('category_id', $category_id)
    //         ->orderBy('sort_order')
    //         ->with(['images', 'category', 'types', 'addons.values', 'attributes.values'])
    //         ->get();

    //     return [$branchProducts, $childrenRecursive];
    // }

    // }
    function checkOverridePriceAttribute($product_id, $attribute_id = null)
    {
        return ProductAttribute::whereProductId($product_id)
            ->whereOverridePrice(1)
            ->when($attribute_id !== null, fn($query) => $query->where('id', '!=', $attribute_id))
            ->exists();
    }

    function deleteProductPhotos($id)
    {
        $productImage = ProductImage::where('id', $id)->firstorfail();
        File::delete(public_path('uploads/product/' . $this->getImageName('product', $productImage->image)));
        return $productImage->delete();
    }

    public function activeWhereNotIn($key, $values, $relations = [])
    {
        return Product::active()->whereNotIn($key, $values)->with($relations)->get();
    }
}
