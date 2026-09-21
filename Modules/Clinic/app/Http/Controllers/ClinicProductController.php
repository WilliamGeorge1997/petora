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
use Modules\Clinic\Services\ClinicProductService;
use Modules\Product\Models\Product;
use Modules\Product\Services\ProductService;

#[Middleware('auth:admin')]
#[Middleware('permission:Edit-clinic')]
class ClinicProductController extends Controller
{
    public function __construct(private ClinicProductService $clinicProductService) {}

    public function index(Request $request, Clinic $clinic)
    {
        Gate::authorize('viewProducts', $clinic);

        $data = $request->merge(['paginated' => 50])->all();
        $relations = [
            'images',
            'category',
            'sellerImages' => fn($q) => $q->where('product_sellers.clinic_id', $clinic->id),
        ];
        $products = $this->clinicProductService->products($clinic, $data, $relations);
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
        $this->clinicProductService->importAllProducts($clinic);

        return back()->with('success', __('common::message.imported_successfully'));
    }

    public function edit(Clinic $clinic, int $product_id)
    {
        Gate::authorize('update', $clinic);

        $product = app(ProductService::class)->findById($product_id, ['images', 'category']);
        $sellerProduct = $this->clinicProductService->findProductSeller($clinic, $product_id, ['images']);

        return view('clinic::products.edit', compact('clinic', 'product', 'sellerProduct'));
    }

    public function update(ClinicProductRequest $request, Clinic $clinic, int $product_id)
    {
        Gate::authorize('update', $clinic);

        $dto = ClinicProductDto::fromRequest($request);
        $this->clinicProductService->updateProductSeller($clinic, $product_id, $dto);

        return redirect()->route('admin.clinic.products.index', $clinic->id)->with('success', __('common::message.updated_successfully'));
    }

    public function activate(Clinic $clinic, Product $product)
    {
        Gate::authorize('update', $clinic);

        $sellerProduct = $this->clinicProductService->activate($clinic, $product);

        return success(
            true,
            $sellerProduct->is_active ? __('clinic::message.activated') : __('clinic::message.deactivated'),
            $sellerProduct
        );
    }

    public function destroy(Clinic $clinic, Product $product)
    {
        Gate::authorize('update', $clinic);
        $this->clinicProductService->detachProduct($clinic, $product);

        return success(true, __('product::message.deleted'));
    }

    public function destroyImage(Clinic $clinic, int $product_id, int $image_id)
    {
        Gate::authorize('update', $clinic);
        $this->clinicProductService->deleteProductSellerImage($image_id);

        return success(true, __('product::message.deleted'));
    }
}
