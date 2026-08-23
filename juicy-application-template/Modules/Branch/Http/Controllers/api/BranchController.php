<?php

namespace Modules\Branch\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Modules\Admin\DTO\BranchManagerDto;
use Modules\Branch\DTO\BranchDto;
use Modules\Branch\DTO\BranchSettingDto;
use Modules\Branch\Http\Requests\BranchRequest;
use Modules\Branch\Service\BranchService;
use Modules\Common\Helper\EnctyptionService;

class BranchController extends Controller
{
    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    private function branchApiRelations(): array
    {
        return [
            'orderMethods' => function ($query) {
                return $query->orderBy('id');
            },
            'paymentMethods',
            'deliveryCharges',
            'workingHours',
            'settings',
            'deliveryAreas',
            'orderDiscounts'
        ];
    }

    public function getByQrCode($token)
    {
        $qr_code = EnctyptionService::decrypt($token);
        $branch = $this->branchService->findByQrCode($qr_code, $this->branchApiRelations());

        if (!$branch) {
            return return_msg(false, 'Branch not found', null, 'not_found');
        }

        return return_msg(true, 'Branch data fetched successfully', $branch);
    }

    public function getByKey($key)
    {
        $relations = $this->branchApiRelations();

        //Case of short url or slug
        if (strpos($key, '.') === 2) {
            $branch = $this->branchService->findByCode($key, $relations);
        } else {
            $branch = $this->branchService->findBySlug($key, $relations);
        }

        if (!$branch) {
            return return_msg(false, 'Branch not found', null, 'not_found');
        }

        return return_msg(true, 'Branch data fetched successfully', $branch);
    }

    public function index()
    {
        $branches = $this->branchService->getActiveBranches();
        return return_msg(true, 'Branches fetched successfully', $branches);
    }

    public function store(BranchRequest $request)
    {
        $branchData = (new BranchDto($request))->dataFromRequest();
        $branchManagerData = (new BranchManagerDto($request))->dataFromRequest();
        $branchSettingData = (new BranchSettingDto($request))->dataFromRequest();
        $branch = $this->branchService->save($branchData, $branchManagerData, $branchSettingData);
        return return_msg(true, 'Branch created successfully', $branch);
    }
}
