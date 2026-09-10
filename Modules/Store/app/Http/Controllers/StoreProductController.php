<?php

namespace Modules\Store\Http\Controllers;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Imports\SellerProductImport;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Enums\AdminRole;
use Modules\Store\Models\Store;

#[Middleware('auth:admin')]
#[Middleware('role:'.AdminRole::SuperAdmin->value)]
#[Middleware('permission:Edit-store')] // editing store's products requires edit store permission
class StoreProductController extends Controller
{
    public function index(Request $request, Store $store)
    {
        if ($request->ajax()) {
            return success(true, 'Fetched', $store->products);
        }
        $products = $store->products()->paginate(50);

        return view('store::products.index', compact('store', 'products'));
    }

    public function export(Store $store)
    {
        return Excel::download(new ProductExport($store), 'store_products.xlsx');
    }

    public function import(Request $request, Store $store)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new SellerProductImport($store), $request->file('file'));

        return back()->with('success', __('common::message.imported_successfully') ?? 'Products imported successfully');
    }
}
