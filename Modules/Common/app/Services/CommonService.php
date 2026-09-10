<?php

namespace Modules\Common\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Common\Models\Setting;
use Spatie\Activitylog\Models\Activity;

class CommonService
{
    use UploaderHelper;

    public function findAll(): Collection
    {
        return Setting::all();
    }

    public function save(array $data): void
    {
        foreach ($data as $key => $datum) {
            if ($datum instanceof UploadedFile) {
                $old_setting = Setting::where('key', $key)->first();
                if ($old_setting && $old_setting->getRawOriginal('value')) {
                    $this->deleteImage($old_setting->getRawOriginal('value'), 'setting');
                }

                $imageName = $this->uploadImage($datum, 'setting');
                $datum = $imageName;
            }

            Setting::where('key', $key)->update(['value' => $datum]);
        }
    }

    public function logs(array $data): Collection
    {
        $query = Activity::with('causer')->latest();

        return getCaseCollection($query, $data);
    }

    public function removeImage(string $path): bool
    {
        if (file_exists(public_path($path))) {
            return unlink(public_path($path));
        }

        return false;
    }
}
