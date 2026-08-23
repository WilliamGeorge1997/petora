<?php


namespace Modules\Common\Helper;


use Intervention\Image\Facades\Image;

trait UploaderHelper
{
    public function uploadMedia($fileFromRequest, $fileFolder, $resize = false, $minimize = false, $quality = 50)
    {
        $extension = strtolower($fileFromRequest->getClientOriginalExtension());

        $motionTypes = ['gif', 'svg'];
        $videoTypes  = ['mp4', 'webm', 'mov', 'avi', 'ogg'];
        $imageTypes  = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $motionTypes) || in_array($extension, $videoTypes)) {
            return $this->uploadFile($fileFromRequest, $fileFolder);
        }
        if (in_array($extension, $imageTypes)) {
            return $this->upload($fileFromRequest, $fileFolder, $resize, $minimize, $quality);
        }
        return $this->upload($fileFromRequest, $fileFolder, $resize, $minimize, $quality);
    }


    public function upload($imageFromRequest, $imageFolder, $resize = false, $minimize = false, $quality = 50)
    {

        if (!file_exists(public_path('uploads/' . $imageFolder))) {
            mkdir(public_path('uploads/' . $imageFolder), 0777, true);
        }
        $originalName = pathinfo($imageFromRequest->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = time() . '_' . $originalName . '.webp';
        $location = public_path('uploads/' . $imageFolder . '/' . $fileName);

        $image = Image::make($imageFromRequest);
        // $image->resize(500,500);
        if ($minimize == true) {
            $image->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $image->save($location, $quality, 'webp');

        # Optional Resize.
        if ($resize == true) {
            $image->resize(100, 70);
            $newlocation = public_path('uploads/' . $imageFolder . '/thumb' . '/' . $fileName);
            $image->save($newlocation, 40, 'webp');
        }


        return $fileName;
    }

    public function uploadFile($fileFromRequest, $fileFolder)
    {

        //        $fileName = time().'.'.$fileFromRequest->getClientOriginalExtension();
        $fileName = time() . '.' . $fileFromRequest->getClientOriginalName();
        $location = public_path('uploads/' . $fileFolder . '/');
        $fileFromRequest->move($location, $fileName);

        return $fileName;
    }

    public function getImageName($folderName, $imagePath)
    {
        $needle = $folderName . '/';
        return substr($imagePath, strpos($imagePath, $needle) + strlen($needle));
    }
}
