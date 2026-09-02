<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Services\ProductService;
use Modules\Product\Transformers\ProductResource;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request, int $seller_id, int $category_id)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $relations = ['images'];
        $sellerType = $request->is('*stores*') ? 'store' : 'clinic';
        $products = $this->productService->byCategoryAndSeller($category_id, $sellerType, $seller_id, $data, $relations);
        
        return success(true, __('product::message.fetched'), paginatedResource($products, ProductResource::class));
    }

    public function show(Request $request, int $seller_id, int $product_id)
    {
        $type = $request->is('*stores*') ? 'stores' : 'clinics';

        $product = $this->productService->findById(
            $product_id,
            [
                'images',
                $type => function ($q) use ($type, $seller_id) {
                    $q->where("$type.id", $seller_id);
                }
            ]
        );

        return success(true, __('product::message.fetched'), new ProductResource($product));
    }
}
