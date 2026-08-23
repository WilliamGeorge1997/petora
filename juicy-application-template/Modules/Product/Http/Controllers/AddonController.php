<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Helper\UploaderHelper;
use Modules\Product\Http\Requests\AddonRequest;
use Modules\Product\Service\AddonService;
use Modules\Product\Validation\ProductValidation;

class AddonController extends Controller
{
    use UploaderHelper, ProductValidation;
    private $addonService;
    public function __construct(AddonService $addonService)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->addonService = $addonService;
        $this->middleware('permission:Index-addon|Create-addon|Edit-addon|Delete-addon', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-addon', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit-addon', ['only' => ['edit', 'update', 'activate', 'sort', 'reorder']]);
        $this->middleware('permission:Delete-addon', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $data['paginated'] = 50;

        $addons = $this->addonService->findAll($data);
        if ($request->ajax()) {
            return response()->json(['data' => $addons->items()]);
        }
        return view('product::addons.index', compact('addons'));
    }


    public function create()
    {
        return view('product::addons.create');
    }

    public function store(AddonRequest $request)
    {
        $data = $request->all();
        $addon = $this->addonService->store($data);
        $addon_values_data = $this->formatAddonValues($addon['id'], $data['addon_values'], $request);
        $this->addonService->storeValues($addon_values_data);
        return redirect('admin/addons')->with('created', 'created');
    }

    function formatAddonValues($addon_id, $addon_values, $request)
    {
        $values = [];
        foreach ($addon_values as $key => $value) {
            if ($request->isMethod('put')) {
                $values[$key]['id'] = $value['id'] ? $value['id'] : null;
            }
            $values[$key]['title'] = ['en' => $value['value_en'], 'ar' => $value['value_ar']];
            $values[$key]['price'] = $value['price'];
            if ($value['image'] ?? null) {
                $values[$key]['image'] = $value['image'];
            }
            $values[$key]['addon_id'] = $addon_id;
        }
        return $values;
    }


    public function edit($id)
    {
        $addon = $this->addonService->find($id, ['values']);
        return view('product::addons.edit', compact('addon'));
    }

    public function update($id, AddonRequest $request)
    {
        $data = $request->all();
        $this->addonService->update($id, $data);
        $addon_values_data = $this->formatAddonValues($id, $data['addon_values'], $request);
        $this->addonService->updateValues($id, $addon_values_data);
        return redirect('admin/addons')->with('updated', 'updated');
    }

    public function destroy($id)
    {
        $this->addonService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->addonService->activate($id);
        return redirect('admin/addons')->with('updated', 'updated');
    }

    public function sort(Request $request)
    {
        $branch_id = getBranchId($request);
        $addons = $this->addonService->findByBranch($branch_id);
        return view('product::addons.sort', compact('addons', 'branch_id'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'branch_id'    => 'required|exists:branches,id',
            'sort_order'   => 'required|array',
            'sort_order.*' => 'integer|exists:addons,id',
        ]);
        $this->addonService->reorder($request['sort_order']);
        return redirect('admin/addons')->with('updated', 'updated');
    }
}
