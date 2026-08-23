<?php

namespace Modules\Branch\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Modules\Branch\DTO\RegistrationDto;
use Modules\Branch\Http\Requests\RegistrationRequest;
use Modules\Branch\Service\RegistrationService;

class RegistrationController extends Controller
{
    public function __construct(
        private RegistrationService $registrationService
    ) {}

    public function store(RegistrationRequest $request)
    {
        $data = (new RegistrationDto($request))->dataFromRequest();
        $registration = $this->registrationService->save($data);

        pushBranchRegistrationNotify($registration);

        return return_msg(true, 'Registration created successfully', $registration);
    }
}
