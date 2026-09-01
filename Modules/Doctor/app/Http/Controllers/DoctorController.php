<?php

namespace Modules\Doctor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Doctor\DTOs\DoctorDto;
use Modules\Doctor\Http\Requests\DoctorRequest;
use Modules\Doctor\Models\Doctor;
use Modules\Doctor\Services\DoctorService;
use Illuminate\Support\Facades\Gate;
use Modules\Doctor\ViewModels\DoctorViewModel;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-doctor|Create-doctor|Edit-doctor|Delete-doctor', only: ['index', 'store'])]
#[Middleware('permission:Create-doctor', only: ['create', 'store'])]
#[Middleware('permission:Edit-doctor', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-doctor', only: ['destroy'])]
class DoctorController extends Controller
{
    public function __construct(private DoctorService $doctorService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $doctors = $this->doctorService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('doctor::message.fetched'), $doctors->items());
        }
        return view('doctor::doctors.index', compact('doctors'));
    }

    public function create()
    {
        $viewModel = new DoctorViewModel();
        return view('doctor::doctors.create', compact('viewModel'));
    }

    public function store(DoctorRequest $request)
    {
        $dto = DoctorDto::fromRequest($request);
        $this->doctorService->save($dto);
        return to_route('admin.doctor.index')->with('success', __('doctor::message.created'));
    }

    public function edit(Doctor $doctor)
    {
        Gate::authorize('update', $doctor);
        $viewModel = new DoctorViewModel();
        return view('doctor::doctors.edit', compact('viewModel', 'doctor'));
    }

    public function update(DoctorRequest $request, Doctor $doctor)
    {
        Gate::authorize('update', $doctor);
        $dto = DoctorDto::fromRequest($request);
        $this->doctorService->update($doctor, $dto);
        return to_route('admin.doctor.index')->with('success', __('doctor::message.updated'));
    }

    public function destroy(Doctor $doctor)
    {
        $this->doctorService->delete($doctor);
        return success(true, __('doctor::message.deleted'));
    }

    public function activate(Doctor $doctor)
    {
        $doctor = $this->doctorService->activate($doctor);
        return success(
            true,
            $doctor->is_active ?  __('doctor::message.activated') :  __('doctor::message.deactivated'),
            $doctor
        );
    }
}
