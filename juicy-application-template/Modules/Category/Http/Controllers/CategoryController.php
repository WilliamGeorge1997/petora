<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\DTO\CategoryDto;
use Modules\Branch\Service\BranchService;
use Modules\Common\Helper\UploaderHelper;
use Illuminate\Contracts\Support\Renderable;
use Modules\Category\Service\CategoryService;
use Modules\Category\ViewModel\CategoryViewModel;
use Modules\Category\Validation\CategoryValidation;

class CategoryController extends Controller
{
    use UploaderHelper, CategoryValidation;
    private $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->categoryService = $categoryService;
        $this->middleware('permission:Index-category|Create-category|Edit-category|Delete-category', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-category', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit-category', ['only' => ['edit', 'update', 'activate', 'sort', 'updateSortOrder']]);
        $this->middleware('permission:Delete-category', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['paginated'] = 50;
        if (
            $request->has('category_id') && $request['category_id'] != null
            && $request['category_id'] != ''
        ) {
            $categories = $this->categoryService->findBy('category_id', $request['category_id'], ['parent.parent']);
        } else {
            $categories = $this->categoryService->findAll($data, ['parent.parent']);
        }
        if ($request->ajax()) {
            return response()->json(['data' => $categories->items()]);
        }
        return view('category::categories.index', ['categories' => $categories, 'request' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $theme = (new BranchService())->getThemeByLoginAdmin();
        $viewModel = (new CategoryViewModel());
        return view('category::categories.create', compact('viewModel', 'theme'));
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $validation = $this->validateStore($data);
        if ($validation->fails()) return redirect()->back()->withInput()->withErrors($validation);
        $data = (new CategoryDto($request))->dataFromRequest();
        $category = $this->categoryService->save($data);
        return redirect('/admin/categories')->with('created', 'created');
    }



    public function edit($id)
    {
        $relations = ['branch.settings:branch_id,theme'];
        $category = $this->categoryService->findById($id, $relations);
        $theme = $category->branch->settings->theme ?? null;
        $viewModel = (new CategoryViewModel());
        return view('category::categories.edit', compact('category', 'viewModel', 'theme'));
    }


    public function update(Request $request, $id)
    {
        $data = $request->except('_token');
        $validation = $this->validateUpdate($data);
        if ($validation->fails()) return redirect()->back()->withErrors($validation);
        $data = (new CategoryDto($request))->dataFromRequest();
        $this->categoryService->update($id, $data);
        return redirect('admin/categories')->with('updated', 'updated');
    }

    public function destroy($id, Request $request)
    {
        $this->categoryService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->categoryService->activate($id);
        return redirect('admin/categories')->with('updated', 'updated');
    }

    public function sort(Request $request)
    {
        $branch_id = getBranchId($request);
        $categories = $this->categoryService->mainCategoriesByBranchId($branch_id);
        return view('category::categories.sort', compact('categories', 'branch_id'));
    }

    public function updateSortOrder(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'sort_order'   => 'required|array',
            'sort_order.*' => 'integer|exists:categories,id',
        ]);
        $this->categoryService->reorder($request['sort_order']);
        return redirect('admin/categories')->with('updated', 'updated');
    }
}
