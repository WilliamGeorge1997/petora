<?php

namespace Modules\Branch\Service;

use Modules\Branch\DTO\RegistrationDto;
use Modules\Branch\Entities\Registration;
use Modules\Common\Helper\UploaderHelper;
use Illuminate\Support\Facades\File;

class RegistrationService
{
    use UploaderHelper;

    public function findById($id)
    {
        return Registration::findOrFail($id);
    }

    public function findAll(array $data)
    {
        $query = Registration::query()->latest('id');
        return getCaseCollection($query, $data);
    }

    public function save(array $data)
    {
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imageName = $this->upload($image, 'registration');
            $data['image'] = $imageName;
        }
        return Registration::create($data);
    }

    public function delete($id)
    {
        $registration = $this->findById($id);
        if ($registration->image) {
            File::delete(public_path('uploads/registration/' . $this->getImageName('registration', $registration->image)));
        }
        return $registration->delete();
    }

    public function completed($id)
    {
        $registration = $this->findById($id);
        $registration->is_completed = !$registration->is_completed;
        $registration->save();
        return $registration;
    }
}
