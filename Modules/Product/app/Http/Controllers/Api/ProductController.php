<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Services\ProductService;
use Modules\Product\Models\Product;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['paginated' => 50])->all();
        $products = $this->productService->active($data);
        return success(true, __('product::message.fetched'), $products);
    }
    
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }
        return success(true, __('product::message.fetched'), $product);
    }
}
