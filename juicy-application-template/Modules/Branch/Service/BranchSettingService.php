<?php

namespace Modules\Branch\Service;

use Illuminate\Support\Facades\File;
use Modules\Branch\Entities\Branch;
use Modules\Branch\Entities\BranchSetting;
use Modules\Common\Helper\UploaderHelper;

class BranchSettingService
{
    use UploaderHelper;

    public function save(Branch $branch, array $settingData): void
    {
        if (request()->hasFile('logo')) {
            File::delete(public_path('uploads/branch/logo/' . $this->getImageName('branch/logo', $branch->settings?->logo)));
            $settingData['logo'] = $this->upload(request()->file('logo'), 'branch/logo', false, false, 80);
        }

        if (request()->hasFile('app_background_image')) {
            if ($branch->settings?->app_background_image) {
                File::delete(public_path('uploads/branch/setting/' . $this->getImageName('branch/setting', $branch->settings?->app_background_image)));
            }
            $settingData['app_background_image'] = $this->upload(request()->file('app_background_image'), 'branch/setting', false, false, 80);
        }

        if (request()->hasFile('app_offer_image')) {
            if ($branch->settings?->app_offer_image) {
                File::delete(public_path('uploads/branch/setting/' . $this->getImageName('branch/setting', $branch->settings?->app_offer_image)));
            }
            $settingData['app_offer_image'] = $this->upload(request()->file('app_offer_image'), 'branch/setting', false, false, 80);
        }

        if (request()->hasFile('header_image')) {
            if ($branch->settings?->header_image) {
                File::delete(public_path('uploads/branch/setting/' . $this->getImageName('branch/setting', $branch->settings?->header_image)));
            }
            $settingData['header_image'] = $this->upload(request()->file('header_image'), 'branch/setting', false, false, 80);
        }

        $branch->settings()->updateOrCreate(['branch_id' => $branch->id], $settingData);
    }

    public function deleteSettingImage($request)
    {
        $field = $request->field;
        $setting = BranchSetting::where('branch_id', $request->branch_id)->firstOrFail();
        $fileName = $setting->$field;
        if (!$fileName)
            return false;
        if ($field == 'logo') {
            $imageName = $this->getImageName('branch/logo', $fileName);
            File::delete(public_path('uploads/branch/logo/' . $imageName));
        } else {
            $imageName = $this->getImageName('branch/setting', $fileName);
            File::delete(public_path('uploads/branch/setting/' . $imageName));
        }
        $setting->update([$field => null]);
        return true;
    }
}
