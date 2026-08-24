<?php

namespace Modules\Common\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait UploaderHelper
{
    protected string $disk = 'public';

    protected function storage()
    {
        return Storage::disk($this->disk);
    }

    public function uploadImage(UploadedFile $file, string $dir, int $quality = 70): string
    {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $path = "uploads/{$dir}";

        Image::fromUpload($file)
            ->quality($quality)
            ->storePubliclyAs($path, $fileName, $this->disk);

        return $fileName;
    }

    public function deleteImage(string $fileName, string $dir): bool
    {
        $file = "uploads/{$dir}/{$fileName}";

        if ($this->storage()->exists($file)) {
            return $this->storage()->delete($file);
        }

        return false;
    }
}
