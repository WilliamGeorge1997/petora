<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\DTO\OrderMethodDto;
use Modules\Order\Service\OrderMethodService;
use Modules\Common\Helper\UploaderHelper;
use Modules\Order\Http\Requests\OrderMethodRequest;

class OrderMethodController extends Controller
{
    use UploaderHelper;
    private $OrderMethodService;
    public function __construct(OrderMethodService $OrderMethodService)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->OrderMethodService = $OrderMethodService;
        $this->middleware('permission:Index-ordermethod|Create-ordermethod|Edit-ordermethod|Delete-ordermethod', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-ordermethod', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit-ordermethod', ['only' => ['edit', 'update', 'activate']]);
        $this->middleware('permission:Delete-ordermethod', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $ordermethods = $this->OrderMethodService->findAll();
        if ($request->ajax())
            return response()->json(['data' => $ordermethods]);

        return view('order::ordermethods.index', ['ordermethods' => $ordermethods]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('order::ordermethods.create');
    }

    public function store(OrderMethodRequest $request)
    {
        $this->OrderMethodService->save((new OrderMethodDto($request))->dataFromRequest());
        return redirect('/admin/ordermethods')->with('created', 'created');
    }

    public function edit($id)
    {
        $ordermethods = $this->OrderMethodService->findById($id);
        return view('order::ordermethods.edit', compact('ordermethods'));
    }


    public function update(OrderMethodRequest $request, $id)
    {
        $this->OrderMethodService->update($id, (new OrderMethodDto($request))->dataFromRequest());
        return redirect('admin/ordermethods')->with('updated', 'updated');
    }

    public function destroy($id, Request $request)
    {
        $this->OrderMethodService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->OrderMethodService->activate($id);
        return redirect('admin/ordermethods')->with('updated', 'updated');
    }
}
