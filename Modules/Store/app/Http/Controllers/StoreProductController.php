<?php

namespace Modules\Store\Http\Controllers;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Imports\SellerProductImport;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

use Modules\Product\Services\ProductService;
use Modules\Store\DTOs\StoreProductDto;
use Modules\Store\Http\Requests\StoreProductRequest;
use Modules\Store\Models\Store;
use Modules\Product\Services\SellerProductService;

#[Middleware('auth:admin')]
#[Middleware('permission:Edit-store')]
class StoreProductController extends Controller
{
    public function __construct(private SellerProductService $sellerProductService) {}

    public function index(Request $request, Store $store)
    {
        Gate::authorize('viewProducts', $store);
        $data = $request->merge(['paginated' => 50])->all();
        $relations = [
            'images',
            'category',
            'sellerImages' => fn($q) => $q->where('seller_product.store_id', $store->id),
        ];
        $products = $this->sellerProductService->findAllBySeller($store, $data, $relations);
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
        $this->sellerProductService->importAllToSeller($store);
        return back()->with('success', __('common::message.imported_successfully'));
    }

    public function edit(Store $store, int $product_id)
    {
        Gate::authorize('update', $store);
        $product = app(ProductService::class)->findById($product_id, ['images', 'category']);
        $sellerProduct = $this->sellerProductService->findByConditions(['store_id' => $store->id, 'product_id' => $product_id], [], ['images'])->first();
        return view('store::products.edit', compact('store', 'product', 'sellerProduct'));
    }

    public function update(StoreProductRequest $request, Store $store, int $product_id)
    {
        Gate::authorize('update', $store);
        $dto = StoreProductDto::fromRequest($request);
        $sellerProduct = $this->sellerProductService->findByConditions(['store_id' => $store->id, 'product_id' => $product_id])->first();
        $this->sellerProductService->update($sellerProduct, $dto->toArray(), $dto->images);
        return to_route('admin.store.products.index', $store->id)->with('success', __('common::message.updated_successfully'));
    }

    public function activate(Store $store, int $product_id)
    {
        Gate::authorize('update', $store);
        $sellerProduct = $this->sellerProductService->findByConditions(['store_id' => $store->id, 'product_id' => $product_id])->first();
        $sellerProduct = $this->sellerProductService->activate($sellerProduct);
        return success(
            true,
            $sellerProduct->is_active ? __('store::message.activated') : __('store::message.deactivated'),
            $sellerProduct
        );
    }

    public function destroy(Store $store, int $product_id)
    {
        Gate::authorize('update', $store);
        $this->sellerProductService->detach(['store_id' => $store->id, 'product_id' => $product_id]);
        return success(true, __('product::message.deleted'));
    }

    public function destroyImage(Store $store, int $image_id)
    {
        Gate::authorize('update', $store);
        $this->sellerProductService->deleteImage($image_id);
        return success(true, __('product::message.deleted'));
    }
}
