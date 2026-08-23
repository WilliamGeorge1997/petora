<?php

namespace Modules\Gallery\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gallery\DTO\GalleryDto;
use Modules\Gallery\Service\GalleryService;
use Modules\Gallery\Validation\GalleryValidation;
use Modules\Gallery\ViewModel\GallerViewModel;

class GalleryController extends Controller
{
    use GalleryValidation;

    public function __construct(private GalleryService $gallerService)
    {
        $this->middleware(['auth:admin', 'prevent-back-history', 'check.theme6.access']);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $data['paginated'] = 50;
        $relations = ['branch'];
        $galleries = $this->gallerService->findAll($data, $relations);
        if ($request->ajax()) {
            return response()->json(['data' => $galleries->items()]);
        }
        return view('gallery::galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $viewModel = (new GallerViewModel());
        return view('gallery::galleries.create', compact('viewModel'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        $validation = $this->validateStore($data);
        if ($validation->fails()) return redirect()->back()->withInput()->withErrors($validation);
        $data = (new GalleryDto($request))->dataFromRequest();
        $this->gallerService->save($data);
        return redirect('/admin/galleries')->with('created', 'created');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('gallery::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $gallery = $this->gallerService->findById($id);
        return view('gallery::galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');
        $validation = $this->validateUpdate($data);
        if ($validation->fails()) return redirect()->back()->withInput()->withErrors($validation);
        $data = (new GalleryDto($request, true))->dataFromRequest();
        $this->gallerService->update($data, $id);
        return redirect('admin/galleries')->with('updated', 'updated');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->gallerService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->gallerService->activate($id);
        return redirect('admin/galleries')->with('updated', 'updated');
    }

    public function sort(Request $request)
    {
        $branch_id = getBranchId($request);
        $viewModel = (new GallerViewModel());
        $galleries = $this->gallerService->findByBranch($branch_id);
        return view('gallery::galleries.sort', compact('galleries', 'branch_id', 'viewModel'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'branch_id'    => 'required|exists:branches,id',
            'sort_order'   => 'required|array',
            'sort_order.*' => 'integer|exists:galleries,id',
        ]);
        $this->gallerService->reorder($request['sort_order']);
        return redirect('admin/galleries')->with('updated', 'updated');
    }
}
