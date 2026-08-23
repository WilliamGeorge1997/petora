<?php

namespace Modules\Product\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Service\ProductService;
use Modules\Product\Transformers\BranchCategoryProductResource;
use Modules\Product\Transformers\BranchCategoryChildrenResource;


class ProductController extends Controller
{
    private $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function show($id)
    {
        $relations = ['images', 'addons.values', 'sides.values', 'attributes.values'];
        $product = $this->productService->findById($id, $relations);
        return return_msg(true, 'Product Details Fetched Successfully', $product);
    }

    public function branchCategoryProducts($id, $category_id)
    {
        $category = $this->productService->branchCategoryProducts($id, $category_id);
        return return_msg(
            true,
            'Products',
            [
                'products' => BranchCategoryProductResource::collection($category->products),
                'children' => BranchCategoryChildrenResource::collection($category->childs),
            ]
        );
    }
}
