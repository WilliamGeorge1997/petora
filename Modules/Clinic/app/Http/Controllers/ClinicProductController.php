<?php

namespace Modules\Clinic\Http\Controllers;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Imports\SellerProductImport;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Enums\AdminRole;
use Modules\Clinic\Models\Clinic;

#[Middleware('auth:admin')]
#[Middleware('role:'.AdminRole::SuperAdmin->value)]
#[Middleware('permission:Edit-clinic')] // editing clinic's products requires edit clinic permission
class ClinicProductController extends Controller
{
    public function index(Request $request, Clinic $clinic)
    {
        if ($request->ajax()) {
            return success(true, 'Fetched', $clinic->products);
        }
        $products = $clinic->products()->paginate(50);

        return view('clinic::products.index', compact('clinic', 'products'));
    }

    public function export(Clinic $clinic)
    {
        return Excel::download(new ProductExport($clinic), 'clinic_products.xlsx');
    }

    public function import(Request $request, Clinic $clinic)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new SellerProductImport($clinic), $request->file('file'));

        return back()->with('success', __('common::message.imported_successfully') ?? 'Products imported successfully');
    }
}
