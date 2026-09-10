<?php

namespace Modules\Product\Http\Controllers;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Product\DTOs\ProductDto;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Models\Product;
use Modules\Product\Services\ProductService;
use Modules\Product\ViewModels\ProductViewModel;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-product|Create-product|Edit-product|Delete-product', only: ['index', 'export', 'store'])]
#[Middleware('permission:Create-product', only: ['create', 'store'])]
#[Middleware('permission:Edit-product', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-product', only: ['destroy'])]
class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $relations = ['category', 'images'];
        $products = $this->productService->findAll($data, $relations);
        if ($request->ajax()) {
            return success(true, __('product::message.fetched'), $products->items());
        }

        return view('product::products.index', compact('products'));
    }

    public function create()
    {
        $viewModel = new ProductViewModel;

        return view('product::products.create', compact('viewModel'));
    }

    public function store(ProductRequest $request)
    {
        $dto = ProductDto::fromRequest($request);
        $this->productService->save($dto);

        return to_route('admin.product.index')->with('success', __('product::message.created'));
    }

    public function edit(Product $product)
    {
        $viewModel = new ProductViewModel;

        return view('product::products.edit', compact('product', 'viewModel'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $dto = ProductDto::fromRequest($request);
        $this->productService->update($product, $dto);

        return to_route('admin.product.index')->with('success', __('product::message.updated'));
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return success(true, __('product::message.deleted'));
    }

    public function export()
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    }

    public function activate(Product $product)
    {
        $product = $this->productService->activate($product);

        return success(
            true,
            $product->is_active ? __('product::message.activated') : __('product::message.deactivated'),
            $product
        );
    }
}
