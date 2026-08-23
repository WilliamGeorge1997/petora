<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Service\SideService;
use Modules\Common\Helper\UploaderHelper;
use Modules\Product\Http\Requests\SideRequest;
use Modules\Product\Validation\ProductValidation;

class SideController extends Controller
{
    use UploaderHelper, ProductValidation;
    private $sideService;
    public function __construct(SideService $sideService)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->sideService = $sideService;
        $this->middleware('permission:Index-side|Create-side|Edit-side|Delete-side', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-side', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit-side', ['only' => ['edit', 'update', 'activate', 'sort', 'reorder']]);
        $this->middleware('permission:Delete-side', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $data['paginated'] = 50;

        $sides = $this->sideService->findAll($data);
        if ($request->ajax()) {
            return response()->json(['data' => $sides->items()]);
        }
        return view('product::sides.index', compact('sides'));
    }


    public function create()
    {
        return view('product::sides.create');
    }

    public function store(SideRequest $request)
    {
        $data = $request->all();
        $side = $this->sideService->store($data);
        $side_values_data = $this->formatSideValues($side['id'], $data['side_values'], $request);
        $this->sideService->storeValues($side_values_data);
        return redirect('admin/sides')->with('created', 'created');
    }

    function formatSideValues($side_id, $side_values, $request)
    {
        $values = [];
        foreach ($side_values as $key => $value) {

            if ($request->isMethod('put')) {
                $values[$key]['id'] = $value['id'] ? $value['id'] : null;
            }

            $values[$key]['title'] = ['en' => $value['value_en'], 'ar' => $value['value_ar']];
            if ($value['image'] ?? null) {
                $values[$key]['image'] = $value['image'];
            }
            $values[$key]['side_id'] = $side_id;
        }
        return $values;
    }


    public function edit($id)
    {
        $side = $this->sideService->find($id, ['values']);
        return view('product::sides.edit', compact('side'));
    }

    public function update($id, SideRequest $request)
    {
        $data = $request->all();
        $this->sideService->update($id, $data);
        $side_values_data = $this->formatSideValues($id, $data['side_values'], $request);
        $this->sideService->updateValues($id, $side_values_data);
        return redirect('admin/sides')->with('updated', 'updated');
    }

    public function destroy($id)
    {
        $this->sideService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->sideService->activate($id);
        return redirect('admin/sides')->with('updated', 'updated');
    }

    public function sort(Request $request)
    {
        $branch_id = getBranchId($request);
        $sides = $this->sideService->findByBranch($branch_id);
        return view('product::sides.sort', compact('sides', 'branch_id'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'branch_id'    => 'required|exists:branches,id',
            'sort_order'   => 'required|array',
            'sort_order.*' => 'integer|exists:sides,id',
        ]);
        $this->sideService->reorder($request['sort_order']);
        return redirect('admin/sides')->with('updated', 'updated');
    }
}
