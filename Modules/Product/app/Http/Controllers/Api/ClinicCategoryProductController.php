<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Services\ProductService;

class ClinicCategoryProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request, int $seller_id, int $category_id)
    {
        $products = $this->productService->byCategoryAndSeller($category_id, $seller_id, 'clinic', $request->all());
        return success(true, __('product::message.fetched'), $products);
    }
}
