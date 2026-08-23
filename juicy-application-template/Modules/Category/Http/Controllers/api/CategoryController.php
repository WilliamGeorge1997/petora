<?php

namespace Modules\Category\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Service\CategoryService;


class CategoryController extends Controller
{
    private $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request, $id)
    {
        $categories = $this->categoryService->branchCategoriesHasProducts($id);
        return return_msg(true, 'All Branch Categories Has Products', $categories);
    }
}
