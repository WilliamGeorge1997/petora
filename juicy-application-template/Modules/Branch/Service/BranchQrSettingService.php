<?php

namespace Modules\Branch\Service;

use Illuminate\Support\Facades\File;
use Modules\Branch\DTO\BranchQrSettingDto;
use Modules\Branch\Entities\Branch;
use Modules\Common\Helper\UploaderHelper;

class BranchQrSettingService
{
    use UploaderHelper;

    public function save(Branch $branch, BranchQrSettingDto $dto): void
    {
        $data = $dto->toArray();
        $existing = $branch->qrSetting;

        if ($dto->qrImage) {
            $data['is_qr_image_enabled'] = $existing?->is_qr_image_enabled ?? $dto->isQrImageEnabled;
            $rawImage = $existing?->getRawOriginal('qr_image');
            if ($rawImage) {
                File::delete(public_path('uploads/branch/setting/' . $this->getImageName('branch/setting', $rawImage)));
            }
            $data['qr_image'] = $this->upload($dto->qrImage, 'branch/setting');
        }

        $branch->qrSetting()->updateOrCreate(
            ['branch_id' => $branch->id],
            $data
        );
    }
}
