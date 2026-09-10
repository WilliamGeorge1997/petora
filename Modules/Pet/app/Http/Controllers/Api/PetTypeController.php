<?php

namespace Modules\Pet\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Pet\Services\PetTypeService;

class PetTypeController extends Controller
{
    public function __construct(private PetTypeService $petTypeService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $petTypes = $this->petTypeService->active($data);

        return success(true, __('pet::message.type_fetched'), $petTypes);
    }
}
