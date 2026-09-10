<?php

namespace Modules\Clinic\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Services\CategoryService;
use Modules\Clinic\Services\ClinicService;
use Modules\Clinic\Transformers\ClinicResource;

class ClinicController extends Controller
{
    public function __construct(private ClinicService $clinicService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $clinics = $this->clinicService->active($data);

        return success(true, __('clinic::message.fetched'), $clinics);
    }

    // Screen after select clinic
    public function show(int $clinic_id, CategoryService $categoryService)
    {
        $relations = ['doctors' => function ($q) {
            $q->active()->latest('id');
        }, 'clinicServices.service' => function ($q) {
            $q->active()->latest('id');
        }];
        $clinic = $this->clinicService->findById($clinic_id, $relations);
        $categories = $categoryService->categoriesHaveProducts('clinic', $clinic_id);
        $clinic->setRelation('categories', $categories);

        return success(true, __('clinic::message.fetched'), new ClinicResource($clinic));
    }
}
