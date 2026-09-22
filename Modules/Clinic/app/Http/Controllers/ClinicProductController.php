<?php

namespace Modules\Clinic\Http\Controllers;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Imports\SellerProductImport;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Clinic\DTOs\ClinicProductDto;
use Modules\Clinic\Http\Requests\ClinicProductRequest;
use Modules\Clinic\Models\Clinic;
use Modules\Product\Services\SellerProductService;

use Modules\Product\Services\ProductService;

#[Middleware('auth:admin')]
#[Middleware('permission:Edit-clinic')]
class ClinicProductController extends Controller
{
    public function __construct(private SellerProductService $sellerProductService) {}

    public function index(Request $request, Clinic $clinic)
    {
        Gate::authorize('viewProducts', $clinic);

        $data = $request->merge(['paginated' => 50])->all();
        $relations = [
            'images',
            'category',
            'sellerImages' => fn($q) => $q->where('seller_product.clinic_id', $clinic->id),
        ];
        $products = $this->sellerProductService->findAllBySeller($clinic, $data, $relations);
        if ($request->ajax()) {
            return success(true, __('product::message.fetched'), $products->items());
        }

        return view('clinic::products.index', compact('clinic', 'products'));
    }

    public function export(Clinic $clinic)
    {
        Gate::authorize('viewProducts', $clinic);

        return Excel::download(new ProductExport($clinic), 'clinic_products.xlsx');
    }

    public function import(Request $request, Clinic $clinic)
    {
        Gate::authorize('update', $clinic);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new SellerProductImport($clinic), $request->file('file'));

        return back()->with('success', __('common::message.imported_successfully'));
    }

    public function importAll(Clinic $clinic)
    {
        Gate::authorize('update', $clinic);
        $this->sellerProductService->importAllToSeller($clinic);

        return back()->with('success', __('common::message.imported_successfully'));
    }

    public function edit(Clinic $clinic, int $product_id)
    {
        Gate::authorize('update', $clinic);

        $product = app(ProductService::class)->findById($product_id, ['images', 'category']);
        $sellerProduct = $this->sellerProductService->findByConditions(['clinic_id' => $clinic->id, 'product_id' => $product_id], [], ['images'])->first();

        return view('clinic::products.edit', compact('clinic', 'product', 'sellerProduct'));
    }

    public function update(ClinicProductRequest $request, Clinic $clinic, int $product_id)
    {
        Gate::authorize('update', $clinic);

        $dto = ClinicProductDto::fromRequest($request);
        $sellerProduct = $this->sellerProductService->findByConditions(['clinic_id' => $clinic->id, 'product_id' => $product_id])->first();
        $this->sellerProductService->update($sellerProduct, $dto->toArray(), $dto->images);

        return to_route('admin.clinic.products.index', $clinic->id)->with('success', __('common::message.updated_successfully'));
    }

    public function activate(Clinic $clinic, int $product_id)
    {
        Gate::authorize('update', $clinic);

        $sellerProduct = $this->sellerProductService->findByConditions(['clinic_id' => $clinic->id, 'product_id' => $product_id])->first();
        $sellerProduct = $this->sellerProductService->activate($sellerProduct);

        return success(
            true,
            $sellerProduct->is_active ? __('clinic::message.activated') : __('clinic::message.deactivated'),
            $sellerProduct
        );
    }

    public function destroy(Clinic $clinic, int $product_id)
    {
        Gate::authorize('update', $clinic);
        $this->sellerProductService->detach(['clinic_id' => $clinic->id, 'product_id' => $product_id]);

        return success(true, __('product::message.deleted'));
    }

    public function destroyImage(Clinic $clinic, int $image_id)
    {
        Gate::authorize('update', $clinic);
        $this->sellerProductService->deleteImage($image_id);

        return success(true, __('product::message.deleted'));
    }
}
