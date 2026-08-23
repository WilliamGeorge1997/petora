<?php

namespace Modules\Branch\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Entities\Registration;
use Modules\Branch\Service\RegistrationService;

class RegistrationController extends Controller
{
    private $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->middleware(['auth:admin']);
        $this->middleware('prevent-back-history')->except('generateQrCode');
        $this->registrationService = $registrationService;
        $this->middleware('permission:Index-branch|Edit-branch|Delete-branch', ['only' => ['index', 'destory', 'completed']]);
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['paginated'] = 50;
        $registrations = $this->registrationService->findAll($data);

        if ($request->ajax()) {
            return response()->json(['data' => $registrations->items()]);
        }

        return view('branch::registrations.index', compact('registrations'));
    }

    public function show($id)
    {
        $registration = $this->registrationService->findById($id);
        return view('branch::registrations.show', compact('registration'));
    }

    public function destroy($id, Request $request)
    {
        $this->registrationService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function completed($id)
    {
        $this->registrationService->completed($id);
        return redirect('admin/registrations')->with('updated', 'updated');
    }
}
