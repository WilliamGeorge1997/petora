<?php

namespace Modules\Package\Service;

use Illuminate\Support\Facades\File;
use Modules\Package\Entities\Package;
use Modules\Common\Helper\UploaderHelper;

class PackageService
{
    use UploaderHelper;
    function findAll($data = [], $relations = [])
    {
        $packages = Package::query();
        return getCaseCollection($packages, $data);
    }

    function active()
    {
        return Package::active()->latest()->get();
    }
    function notSpecial()
    {
        return Package::notSpecial()->latest()->get();
    }
    function findById($id)
    {
        return Package::findOrFail($id);
    }

    function findBy($key, $value)
    {
        return Package::where($key, $value)->get();
    }

    function save($data)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'package');
        }
        $package = Package::create($data);
        return $package;
    }

    function update($id, $data)
    {
        $package = $this->findById($id);
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/package/' . $this->getImageName('package', $package->image)));
            $data['image'] = $this->upload(request()->file('image'), 'package');
        }
        $package->update($data);
        return $package;
    }

    function activate($id)
    {
        $package = $this->findById($id);
        $package->update(['is_active' => !$package->is_active]);
    }
    function delete($id)
    {
        $package = $this->findById($id);
        if ($package->image) {
            File::delete(public_path('uploads/package/' . $this->getImageName('package', $package->image)));
        }
        $package->delete();
    }
}
