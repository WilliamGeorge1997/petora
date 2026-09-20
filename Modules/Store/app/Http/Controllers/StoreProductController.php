<?php

namespace Modules\Store\Http\Controllers;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Imports\SellerProductImport;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Product\Models\Product;
use Modules\Product\Services\ProductService;
use Modules\Store\DTOs\StoreProductDto;
use Modules\Store\Http\Requests\StoreProductRequest;
use Modules\Store\Models\Store;
use Modules\Store\Services\StoreProductService;

#[Middleware('auth:admin')]
#[Middleware('permission:Edit-store')]
class StoreProductController extends Controller
{
    public function __construct(private StoreProductService $storeProductService) {}

    public function index(Request $request, Store $store)
    {
        Gate::authorize('viewProducts', $store);

        $data = $request->merge(['paginated' => 50])->all();
        $relations = [
            'images',
            'category',
            'sellerImages' => fn($q) => $q->where('product_sellers.store_id', $store->id),
        ];
        $products = $this->storeProductService->products($store, $data, $relations);
        if ($request->ajax()) {
            return success(true, __('product::message.fetched'), $products->items());
        }
        return view('store::products.index', compact('store', 'products'));
    }

    public function export(Store $store)
    {
        Gate::authorize('viewProducts', $store);

        return Excel::download(new ProductExport($store), 'store_products.xlsx');
    }

    public function import(Request $request, Store $store)
    {
        Gate::authorize('update', $store);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new SellerProductImport($store), $request->file('file'));

        return back()->with('success', __('common::message.imported_successfully'));
    }

    public function importAll(Store $store)
    {
        Gate::authorize('update', $store);
        $this->storeProductService->importAllProducts($store);
        return back()->with('success', __('common::message.imported_successfully'));
    }

    public function edit(Store $store, int $product_id)
    {
        Gate::authorize('update', $store);

        $product = app(ProductService::class)->findById($product_id, ['images', 'category']);
        $sellerProduct = $this->storeProductService->findProductSeller($store, $product_id, ['images']);

        return view('store::products.edit', compact('store', 'product', 'sellerProduct'));
    }

    public function update(StoreProductRequest $request, Store $store, int $product_id)
    {
        Gate::authorize('update', $store);

        $dto = StoreProductDto::fromRequest($request);
        $this->storeProductService->updateProductSeller($store, $product_id, $dto);

        return redirect()->route('admin.store.products.index', $store->id)->with('success', __('common::message.updated_successfully'));
    }

    public function activate(Store $store, Product $product)
    {
        Gate::authorize('update', $store);

        $sellerProduct = $this->storeProductService->activate($store, $product);

        return success(
            true,
            $sellerProduct->is_active ? __('store::message.activated') : __('store::message.deactivated'),
            $sellerProduct
        );
    }

    public function destroy(Store $store, Product $product)
    {
        Gate::authorize('update', $store);
        $this->storeProductService->detachProduct($store, $product);

        return success(true, __('product::message.deleted'));
    }

    public function destroyImage(Store $store, int $product_id, int $image_id)
    {
        Gate::authorize('update', $store);
        $this->storeProductService->deleteProductSellerImage($image_id);

        return success(true, __('product::message.deleted'));
    }
}
