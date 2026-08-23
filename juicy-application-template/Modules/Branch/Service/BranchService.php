<?php


namespace Modules\Branch\Service;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Builder\Builder;
use Modules\Branch\Entities\Branch;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\File;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Str;
use Modules\Branch\Entities\WorkingHour;
use Modules\Common\Helper\UploaderHelper;
use Modules\Branch\Entities\BranchSetting;
use Modules\Branch\Entities\DeliveryArea;
use Modules\Branch\Entities\DeliveryCharge;
use Modules\Branch\Entities\ShortUrl;

class BranchService
{
    use UploaderHelper;

    function findAll($data = [], $relations = [])
    {
        $query = Branch::with($relations)
            ->withCount('branchProducts')
            ->available()
            ->when($data['theme'] ?? null, function ($q) use ($data) {
                $q->whereHas('settings', function ($q) use ($data) {
                    $q->where('theme', $data['theme']);
                });
            })->latest('id');

        return getCaseCollection($query, $data);
    }

    public function getThemeCounts()
    {
        return BranchSetting::selectRaw('theme, count(*) as count')
            ->groupBy('theme')
            ->pluck('count', 'theme')
            ->all();
    }

    public function findSelection($selections, $data = [], $relations = [])
    {
        $query = Branch::select($selections)->with($relations);
        return getCaseCollection($query, $data);
    }

    public function getActiveBranches()
    {
        return Branch::active()->select(['id', 'title', 'slug', 'image', 'city'])->get();
    }

    function active()
    {
        return Branch::active()->available()->get();
    }

    function filter($data)
    {
        $Branchs = Branch::active()->available();
        if ($data['query'] ?? null) {
            $Branchs = $Branchs->whereTranslationLike('title', '%' . $data['query'] . '%');
        }
        return $Branchs->get();
    }

    function findById($id, $relations = []): Branch
    {
        return Branch::with($relations)->findOrFail($id);
    }

    function findBy($key, $value)
    {
        return Branch::where($key, $value)->get();
    }

    function save($data, $managerData, $settingData)
    {
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imageName = $this->upload($image, 'branch');
            $data['image'] = $imageName;
        }

        $branch = Branch::create($data);

        if (isset($data['delivery_charges']) && !empty($data['delivery_charges'])) {
            $this->saveBranchDeliveryCharge($branch->id, $data['delivery_charges']);
        }
        if (isset($data['delivery_areas']) && !empty($data['delivery_areas'])) {
            $this->saveBranchDeliveryAreas($branch->id, $data['delivery_areas']);
        }
        if (isset($data['working_hours']) && !empty($data['working_hours'])) {
            $this->saveBranchWorkingHours($branch->id, $data['working_hours']);
        }
        if (isset($settingData) && !empty($settingData))
            (new BranchSettingService())->save($branch, $settingData);

        $branch->orderMethods()->sync($data['order_methods']);
        $branch->paymentMethods()->sync($data['payment_methods']);

        if (request()->hasFile('manager_image')) {
            $image = request()->file('manager_image');
            $imageName = $this->upload($image, 'admin');
            $managerData['image'] = $imageName;
        }
        $admin = $branch->admin()->create($managerData);
        $admin->assignRole($managerData['role']);
        return $branch;
    }

    function update(Branch $Branch, $data, $settingData)
    {

        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/branch/' . $this->getImageName('branch', $Branch->image)));
            $image = request()->file('image');
            $imageName = $this->upload($image, 'branch');
            $data['image'] = $imageName;
        }


        $Branch->update($data);

        // Settings saved via BranchSettingController (admin/branches/{id}/settings)
        // if (isset($settingData) && !empty($settingData))
        //     $Branch->load('settings');
        // $this->saveBranchSetting($Branch, $settingData);
        if (isset($settingData) && !empty($settingData)) {
            $Branch->load('settings');
            $this->saveBranchSetting($Branch, $settingData);
        }

        $Branch->deliveryCharges()->delete();
        if (isset($data['delivery_charges']) && !empty($data['delivery_charges'])) {
            $this->saveBranchDeliveryCharge($Branch->id, $data['delivery_charges']);
        }

        $Branch->deliveryAreas()->delete();
        if (isset($data['delivery_areas']) && !empty($data['delivery_areas'])) {
            $this->saveBranchDeliveryAreas($Branch->id, $data['delivery_areas']);
        }

        $Branch->workingHours()->delete();
        if (isset($data['working_hours']) && !empty($data['working_hours'])) {
            $this->saveBranchWorkingHours($Branch->id, $data['working_hours']);
        }

        $Branch->orderMethods()->sync($data['order_methods']);
        $Branch->paymentMethods()->sync($data['payment_methods']);
        return $Branch;
    }

    function activate($id)
    {
        $Branch = $this->findById($id);
        $Branch->is_active = !$Branch->is_active;
        $Branch->save();
    }

    function delete($id)
    {
        $Branch = $this->findById($id);
        File::delete(public_path('uploads/branch/' . $this->getImageName('branch', $Branch->image)));
        $Branch->delete();
    }

    function saveBranchDeliveryCharge($branch_id, $delivery_charges)
    {
        foreach ($delivery_charges as $delivery_charge) {
            if ($delivery_charge['distance'] == null || $delivery_charge['price'] == null)
                continue;
            DeliveryCharge::create([
                'branch_id' => $branch_id,
                'distance' => $delivery_charge['distance'],
                'price' => $delivery_charge['price']
            ]);
        }
    }

    function saveBranchDeliveryAreas($branch_id, $delivery_areas)
    {
        foreach ($delivery_areas as $delivery_area) {
            if ($delivery_area['title'] == null || $delivery_area['price'] == null)
                continue;
            DeliveryArea::create([
                'branch_id' => $branch_id,
                'title' => $delivery_area['title'],
                'price' => $delivery_area['price']
            ]);
        }
    }

    function saveBranchWorkingHours($branch_id, $working_hours)
    {
        foreach ($working_hours as $working_hour) {
            $isOpen24Hours = isset($working_hour['is_open_24_hours']) ? 1 : 0;

            if ($working_hour['day'] == null)
                continue;

            if ($isOpen24Hours == 0 && ($working_hour['from'] == null || $working_hour['to'] == null))
                continue;

            WorkingHour::create([
                'branch_id' => $branch_id,
                'day' => $working_hour['day'],
                'is_open_24_hours' => $isOpen24Hours,
                'from' => $isOpen24Hours ? null : $working_hour['from'],
                'to' => $isOpen24Hours ? null : $working_hour['to'],
            ]);
        }
    }

    // function saveBranchSetting(Branch $branch, $settingData)
    // {
    //     if (request()->hasFile('logo')) {
    //         File::delete(public_path('uploads/branch/logo/' . $this->getImageName('branch/logo', $branch->settings?->logo)));
    //         $settingData['logo'] = $this->upload(request()->file('logo'), 'branch/logo');
    //     }

    //     if (request()->hasFile('app_background_image')) {
    //         if ($branch->settings?->app_background_image)
    //             File::delete(public_path('uploads/branch/setting/' . $this->getImageName('branch/setting', $branch->settings?->app_background_image)));
    //         $settingData['app_background_image'] = $this->upload(request()->file('app_background_image'), 'branch/setting');
    //     }
    //     if (request()->hasFile('app_offer_image')) {
    //         if ($branch->settings?->app_offer_image)
    //             File::delete(public_path('uploads/branch/setting/' . $this->getImageName('branch/setting', $branch->settings?->app_offer_image)));
    //         $settingData['app_offer_image'] = $this->upload(request()->file('app_offer_image'), 'branch/setting');
    //     }

    //     if (request()->hasFile('header_image')) {
    //         if ($branch->settings?->header_image)
    //             File::delete(public_path('uploads/branch/setting/' . $this->getImageName('branch/setting', $branch->settings?->header_image)));
    //         $settingData['header_image'] = $this->upload(request()->file('header_image'), 'branch/setting');
    //     }

    //     if (request()->hasFile('qr_image')) {
    //         if ($branch->settings?->qr_image)
    //             File::delete(public_path('uploads/branch/setting/' . $this->getImageName('branch/setting', $branch->settings?->qr_image)));
    //         $settingData['qr_image'] = $this->upload(request()->file('qr_image'), 'branch/setting');
    //     }

    //     $branch->settings()->updateOrCreate(['branch_id' => $branch->id], $settingData);
    // }

    function deliveryCharges($branch_id)
    {
        return DeliveryCharge::whereBranchId($branch_id)->get();
    }

    //     function generateQrCodeHtml($data, $logo = null ,$size = 500)
    //     {
    //         $qr = QrCode::format('png')
    //             ->size($size)
    //             ->margin(4)
    //             ->eye('circle')
    //             // ->errorCorrection('H')
    //             ->color(0, 0, 0);

    //         // Optional logo
    //         if ($logo) {
    //             $logoPath = public_path("uploads/branch/logo/$logo");
    //             if (file_exists($logoPath)) {
    //                 $qr->merge($logoPath, 0.20, true);
    //             }
    //         }

    //         $qrCode = $qr->generate($data);
    //         $base64 = base64_encode($qrCode);
    //         return '
    // <!DOCTYPE html>
    // <html>
    // <head>
    //     <style>
    //         body {
    //             display: flex;
    //             justify-content: center;
    //             align-items: center;
    //             min-height: 100vh;

    //             margin: 0;
    //         }
    //         img {
    //             width: ' . $size . 'px;
    //             height: ' . $size . 'px;
    //         }
    //     </style>
    // </head>
    // <body>
    //     <img src="data:image/png;base64,' . $base64 . '">
    // </body>
    // </html>';
    //     }

    function generateQrCodeHtml($data, $logo_enable = 0, $logo = null, $size = 500)
    {
        $builder = Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size($size)
            ->margin(10)
            ->foregroundColor(new Color(0, 0, 0));

        if ($logo_enable && $logo) {
            $logoPath = public_path("uploads/branch/setting/$logo");
            if (file_exists($logoPath)) {
                $builder = $builder->logoPath($logoPath)
                    ->logoResizeToWidth((int) ($size * 0.30))
                    ->logoPunchoutBackground(true);
            }
        }

        $result = $builder->build();
        $base64 = base64_encode($result->getString());

        return '
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        img {
            width: ' . $size . 'px;
            height: ' . $size . 'px;
        }
    </style>
</head>
<body>
    <img src="data:image/png;base64,' . $base64 . '">
</body>
</html>';
    }

    public function generateShortUrl($url, $branch): ShortUrl
    {
        $shortUrl = ShortUrl::firstOrCreate(
            ['branch_id' => $branch->id],
            [
                'code' => Str::random(2) . '.' . Str::random(5),
                'url' => $url,
            ]
        );

        if (!$shortUrl->wasRecentlyCreated) {
            $shortUrl->update(['url' => $url]);
        }

        return $shortUrl;
    }

    function findByQrCode($qrCode, $relations = [])
    {
        $branch = Branch::with($relations)->where('qr_code', $qrCode)->first();
        if ($branch) {
            $branch->increment('scan_counter');
        }
        return $branch;
    }

    function findByCode(string $code, $relations = [])
    {
        $shortUrl = ShortUrl::where('code', $code)->first();

        if (!$shortUrl) {
            return null;
        }

        $branch = Branch::with($relations)->active()->find($shortUrl->branch_id);

        if ($branch) {
            $branch->increment('scan_counter');
        }

        return $branch;
    }

    function findBySlug(string $slug, $relations = [])
    {
        $branch = Branch::with($relations)->active()->where('slug', $slug)->first();

        if ($branch) {
            $branch->increment('scan_counter');
        }

        return $branch;
    }

    function updateTheme($id, int $theme)
    {
        $settings = BranchSetting::where('branch_id', $id)->first();

        if ($settings) {
            $settings->update(['theme' => $theme]);
        } else {
            BranchSetting::create([
                'branch_id' => $id,
                'theme' => $theme,
                'currency_ar' => 'ج.م',
                'currency_en' => 'EGP',
                'app_primary_color' => '#000000',
                'app_secondary_color' => '#000000',
                'app_text_color' => '#000000',
                'app_indicator_color' => '#000000'
            ]);
        }
    }


    function getThemeByLoginAdmin()
    {
        if (auth('admin')->check()) {
            return BranchSetting::select('theme')->where('branch_id', auth()->user()->branch_id)->first()?->theme ?? null;
        }
    }

    public function getOfferByBranch($branch_id)
    {
        return BranchSetting::select(['app_offer_image', 'app_offer_product_id', 'app_offer_ends_at', 'app_offer_is_active'])
            ->where('branch_id', $branch_id)->first();
    }

    public function upsertOffer($branch_id, $data)
    {
        $settings = BranchSetting::whereBranchId($branch_id)->first();

        $offerData = [
            'app_offer_product_id' => $data['app_offer_product_id'],
            'app_offer_ends_at' => $data['app_offer_ends_at'],
            'app_offer_is_active' => isset($data['app_offer_is_active']) ? 1 : 0,
        ];

        if (request()->hasFile('app_offer_image')) {
            if ($settings->app_offer_image)
                File::delete(public_path('uploads/branch/setting/' . $this->getImageName('branch/setting', $settings->app_offer_image)));
            $offerData['app_offer_image'] = $this->upload(request()->file('app_offer_image'), 'branch/setting');
        }

        return $settings->update($offerData);
    }

    public function deleteOffer($branch_id)
    {
        $settings = BranchSetting::whereBranchId($branch_id)->first();
        if ($settings->app_offer_image)
            File::delete(public_path('uploads/branch/setting/' . $this->getImageName('branch/setting', $settings->app_offer_image)));

        return $settings->update(
            [
                'app_offer_image' => null,
                'app_offer_product_id' => null,
                'app_offer_ends_at' => null,
                'app_offer_is_active' => 0,
            ]
        );
    }
}
