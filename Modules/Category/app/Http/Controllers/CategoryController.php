<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Category\DTOs\CategoryDto;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Models\Category;
use Modules\Category\Services\CategoryService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-category|Create-category|Edit-category|Delete-category', only: ['index', 'store'])]
#[Middleware('permission:Create-category', only: ['create', 'store'])]
#[Middleware('permission:Edit-category', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-category', only: ['destroy'])]
class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $categories = $this->categoryService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('category::message.fetched'), $categories->items());
        }

        return view('category::categories.index', compact('categories'));
    }

    public function create()
    {
        return view('category::categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $dto = CategoryDto::fromRequest($request);
        $this->categoryService->save($dto);

        return to_route('admin.category.index')->with('success', __('category::message.created'));
    }

    public function edit(Category $category)
    {
        Gate::authorize('update', $category);

        return view('category::categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        Gate::authorize('update', $category);
        $dto = CategoryDto::fromRequest($request);
        $this->categoryService->update($category, $dto);

        return to_route('admin.category.index')->with('success', __('category::message.updated'));
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return success(true, __('category::message.deleted'));
    }

    public function activate(Category $category)
    {
        $category = $this->categoryService->activate($category);

        return success(
            true,
            $category->is_active ? __('category::message.activated') : __('category::message.deactivated'),
            $category
        );
    }
}
