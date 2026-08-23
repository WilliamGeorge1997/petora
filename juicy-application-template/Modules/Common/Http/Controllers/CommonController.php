<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Branch\Service\BranchService;
use Modules\Common\Entities\Setting;
use Spatie\Activitylog\Models\Activity;
use Modules\Common\Helper\UploaderHelper;

class CommonController extends Controller
{
    use UploaderHelper;
    public function home()
    {
        $branches = (new BranchService())->findSelection(['id','title'], [], ['settings:branch_id,logo', 'shortUrl:branch_id,code']);
        return view('common::home.index', compact('branches'));
    }
    public function setting()
    {
        $settings = Setting::where('key', 'not like', 'app_%')->get();

        return view('common::setting.index', ['settings' => $settings]);
    }

    public function appSetting()
    {
        $settings = Setting::where('key', 'like', 'app_%')->get();
        return view('common::setting.app', ['settings' => $settings]);
    }

    public function socialSetting()
    {
        $settings = Setting::whereIn('key', ['facebook', 'youtube', 'instagram', 'x', 'snapchat', 'tiktok', 'whatsapp', 'telegram'])->get();
        return view('common::setting.social', ['settings' => $settings]);
    }

    public function savesetting(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $datum) {
            if (in_array($key, ['logo', 'app_background_image', 'app_offer_image']) && isset($datum)) {
                $old_image = Setting::where('key', $key)->first()['value'];
                if ($old_image) {
                    File::delete(public_path('uploads/setting/' . $old_image));
                }
                $image = $request->file($key);
                $imageName = $this->upload($image, 'setting');
                $datum = $imageName;
            }
            Setting::where('key', $key)->update(['value' => $datum]);
        }
        return back()->with('updated', 'updated');
    }

    public function logs()
    {
        $logs = Activity::with('causer')->latest()->paginate(50);
        return view('common::logs.index', compact('logs'));
    }

    public function viewOrderNotify(Request $request)
    {
        return redirect()->to("admin/orders/$request->OrderId");
    }

    public function removeImage(Request $request)
    {
        $setting = Setting::where('key', $request->key)->first();
        File::delete(public_path('uploads/setting/' . $setting->value));
        $setting->update(['value' => null]);
        return response()->json(['status' => true,]);
    }
}
