<?php

namespace Modules\Common\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Image;

trait UploaderHelper
{
    public function uploadImage(UploadedFile $file, string $module): string
    {
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = "uploads/{$module}";

        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0755, true);
        }

        Image::fromUpload($file)
            ->quality(70)
            ->storePubliclyAs($path, $filename, 'public');

        return $filename;
    }
    
    public function deleteImage(?string $filename, string $module): bool
    {
        if (!$filename) return false;

        $path = "uploads/{$module}/{$filename}";

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        // Fallback for paths that might be directly in public/
        if (file_exists(public_path($path))) {
            return unlink(public_path($path));
        }
        
        return false;
    }
}
