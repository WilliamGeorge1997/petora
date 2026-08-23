<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\DTO\PaymentMethodDto;
use Modules\Order\Service\PaymentMethodService;
use Modules\Common\Helper\UploaderHelper;
use Modules\Order\Http\Requests\OrderMethodRequest;

class PaymentMethodController extends Controller
{
    use UploaderHelper;
    private $PaymentMethodService;
    public function __construct(PaymentMethodService $PaymentMethodService)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->PaymentMethodService = $PaymentMethodService;
        $this->middleware('permission:Index-paymentmethods|Create-paymentmethods|Edit-paymentmethods|Delete-paymentmethods', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-paymentmethods', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit-paymentmethods', ['only' => ['edit', 'update', 'activate']]);
        $this->middleware('permission:Delete-paymentmethods', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $paymentmethods = $this->PaymentMethodService->findAll();
        if ($request->ajax())
            return response()->json(['data' => $paymentmethods]);

        return view('order::paymentmethods.index', ['paymentmethods' => $paymentmethods]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('order::paymentmethods.create');
    }

    public function store(OrderMethodRequest $request)
    {
        $this->PaymentMethodService->save((new PaymentMethodDto($request))->dataFromRequest());
        return redirect('/admin/paymentmethods')->with('created', 'created');
    }

    public function edit($id)
    {
        $paymentmethods = $this->PaymentMethodService->findById($id);
        return view('order::paymentmethods.edit', compact('paymentmethods'));
    }

    public function update(OrderMethodRequest $request, $id)
    {
        $this->PaymentMethodService->update($id, (new PaymentMethodDto($request))->dataFromRequest());
        return redirect('admin/paymentmethods')->with('updated', 'updated');
    }

    public function destroy($id, Request $request)
    {
        $this->PaymentMethodService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->PaymentMethodService->activate($id);
        return redirect('admin/paymentmethods')->with('updated', 'updated');
    }
}
