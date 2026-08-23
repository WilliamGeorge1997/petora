<?php

namespace Modules\Import\Service;

use Illuminate\Support\Facades\DB;
use Modules\Category\Service\CategoryService;
use Modules\Import\DTO\ImportAddonDto;
use Modules\Import\DTO\ImportCategoryDto;
use Modules\Import\DTO\ImportProductDto;
use Modules\Product\Service\AddonService;
use Modules\Product\Service\ProductService;

class ImportService
{
    private CategoryService $categoryService;
    private ProductService $productService;
    private AddonService $addonService;

    public function __construct(
        CategoryService $categoryService,
        ProductService $productService,
        AddonService $addonService
    ) {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->addonService = $addonService;
    }

    public function parse($json)
    {
        $content = trim((string) $json);

        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/i', $content, $matches)) {
            $content = trim($matches[1]);
        }

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON غير صالح: ' . json_last_error_msg());
        }

        if (!is_array($data)) {
            throw new \Exception('يجب أن يكون المحتوى كائن JSON.');
        }

        return $data;
    }

    public function show($data)
    {
        $counts = $this->count($data);
        $categories = [];

        foreach ($data['categories'] ?? [] as $category) {
            $categories[] = $this->prepareCategoryForShow($category);
        }

        return [
            'categories_count' => $counts['categories'],
            'products_count' => $counts['products'],
            'addons_count' => $counts['addons'],
            'addon_values_count' => $counts['addon_values'],
            'categories' => $categories,
            'addons' => $data['addons'] ?? [],
        ];
    }

    private function prepareCategoryForShow($category)
    {
        $productsCount = count($category['products'] ?? []);
        $subcategories = [];

        foreach ($category['subcategories'] ?? [] as $subcategory) {
            $sub = $this->prepareCategoryForShow($subcategory);
            $subcategories[] = $sub;
            $productsCount += count($sub['products'] ?? []);
        }

        $category['subcategories'] = $subcategories;
        $category['products_count'] = $productsCount;

        return $category;
    }

    public function save($branchId, $data)
    {
        // Subscription product count limit
        $newProducts = $this->count($data)['products'];
        $this->productService->assertCanCreateProduct($branchId, $newProducts);

        DB::transaction(function () use ($branchId, $data) {
            foreach ($data['categories'] ?? [] as $category) {
                $this->saveCategory($category, $branchId);
            }

            foreach ($data['addons'] ?? [] as $addon) {
                $this->addonService->createFromImport(
                    (new ImportAddonDto($addon, $branchId))->dataFromImport()
                );
            }
        });

        return $this->count($data);
    }

    private function saveCategory($node, $branchId)
    {
        $category = $this->categoryService->save((new ImportCategoryDto($node, $branchId))->dataFromImport());

        $this->saveProducts($node['products'] ?? [], $branchId, $category->id);

        foreach ($node['subcategories'] ?? [] as $subcategory) {
            $child = $this->categoryService->save(
                (new ImportCategoryDto($subcategory, $branchId, $category->id))->dataFromImport()
            );
            $this->saveProducts($subcategory['products'] ?? [], $branchId, $child->id);
        }
    }

    private function saveProducts($products, $branchId, $categoryId)
    {
        foreach ($products as $product) {
            $this->productService->createFromImport((new ImportProductDto($product, $branchId, $categoryId))->dataFromImport());
        }
    }

    private function count($data)
    {
        $categories = 0;
        $products = 0;
        $addons = 0;
        $addonValues = 0;

        foreach ($data['categories'] ?? [] as $category) {
            $categories++;
            $products += count($category['products'] ?? []);

            foreach ($category['subcategories'] ?? [] as $subcategory) {
                $categories++;
                $products += count($subcategory['products'] ?? []);
            }
        }

        foreach ($data['addons'] ?? [] as $addon) {
            $addons++;
            $addonValues += count($addon['values'] ?? []);
        }

        return [
            'categories' => $categories,
            'products' => $products,
            'addons' => $addons,
            'addon_values' => $addonValues,
        ];
    }
}
