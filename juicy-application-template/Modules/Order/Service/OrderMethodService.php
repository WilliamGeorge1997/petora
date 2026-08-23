<?php


namespace Modules\Order\Service;

use Illuminate\Support\Facades\File;
use Modules\Common\Helper\UploaderHelper;
use Modules\Order\Entities\OrderMethod;

class OrderMethodService
{
    use UploaderHelper;
    function findAll()
    {
        return OrderMethod::all();
    }

    function active()
    {
        return OrderMethod::active()->get();
    }

    function findById($id)
    {
        return OrderMethod::findOrFail($id);
    }

    function findBy($key, $value)
    {
        return OrderMethod::where($key, $value)->get();
    }

    function save($data)
    {
        if (request()->hasFile('image'))
            $data['image'] = $this->upload(request()->file('image'), 'Ordermethod');
        return OrderMethod::create($data);
    }

    function update($id, $data)
    {
        $ordermethods = $this->findById($id);
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/Ordermethod/' . $this->getImageName('Ordermethod', $ordermethods->image)));
            $data['image'] = $this->upload(request()->file('image'), 'Ordermethod');
        }
        $ordermethods->update($data);
        return $ordermethods;
    }

    function activate($id)
    {
        $ordermethods = $this->findById($id);
        $ordermethods->is_active = !$ordermethods->is_active;
        $ordermethods->save();
    }
    function delete($id)
    {
        $ordermethods = $this->findById($id);
        File::delete(public_path('uploads/Ordermethod/' . $this->getImageName('Ordermethod', $ordermethods->image)));
        $ordermethods->delete();
    }
}
