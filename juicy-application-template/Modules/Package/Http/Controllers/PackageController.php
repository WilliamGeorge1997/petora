<?php

namespace Modules\Package\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Package\DTO\PackageDto;
use Modules\Package\Entities\Package;
use Modules\Package\Service\PackageService;
use Illuminate\Contracts\Support\Renderable;
use Modules\Package\Http\Requests\PackageRequest;

class PackageController extends Controller
{
    private $packageService;
    public function __construct(PackageService $packageService)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->middleware('permission:Index-package|Create-package|Edit-package|Delete-package', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-package', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit-package', ['only' => ['edit', 'update', 'activate']]);
        $this->middleware('permission:Delete-package', ['only' => ['destroy']]);
        $this->packageService = $packageService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $data['paginated'] = 50;
        $packages = $this->packageService->findAll($data);
        if ($request->ajax()) {
            return response()->json(['data' => $packages->items()]);
        }
        return view('package::packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('package::packages.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(PackageRequest $request)
    {
        $data = (new PackageDto($request))->dataFromRequest();
        $this->packageService->save($data);
        return redirect('admin/packages')->with('created', 'created');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Package $package)
    {
        return view('package::packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(PackageRequest $request, $id)
    {
        $data = (new PackageDto($request))->dataFromRequest();
        $this->packageService->update($id, $data);
        return redirect('admin/packages')->with('updated', 'updated');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->packageService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->packageService->activate($id);
        return redirect('admin/packages')->with('updated', 'updated');
    }
}
