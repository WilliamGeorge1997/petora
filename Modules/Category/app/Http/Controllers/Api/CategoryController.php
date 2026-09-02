<?php

namespace Modules\Category\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function index(Request $request, int $store_id)
    {
        $request->merge(['paginated' => 50, 'pagination_type' => 'cursor']);
        $categories = $this->categoryService->categoriesHaveProducts('store', $store_id, $request->all());
        return success(true, __('category::message.fetched'), $categories);
    }
}
