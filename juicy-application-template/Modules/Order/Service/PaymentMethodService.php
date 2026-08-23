<?php


namespace Modules\Order\Service;

use Illuminate\Support\Facades\File;
use Modules\Common\Helper\UploaderHelper;
use Modules\Order\Entities\PaymentMethod;

class PaymentMethodService
{
    use UploaderHelper;
    function findAll()
    {
        return PaymentMethod::all();
    }

    function active()
    {
        return PaymentMethod::active()->get();
    }

    function findById($id)
    {
        return PaymentMethod::findOrFail($id);
    }

    function findBy($key, $value)
    {
        return PaymentMethod::where($key, $value)->get();
    }

    function save($data)
    {
        if (request()->hasFile('image'))
            $data['image'] = $this->upload(request()->file('image'), 'PaymentMethod');
        return PaymentMethod::create($data);
    }

    function update($id, $data)
    {
        $PaymentMethods = $this->findById($id);
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/PaymentMethod/' . $this->getImageName('PaymentMethod', $PaymentMethods->image)));
            $data['image'] = $this->upload(request()->file('image'), 'PaymentMethod');
        }
        $PaymentMethods->update($data);
        return $PaymentMethods;
    }

    function activate($id)
    {
        $PaymentMethods = $this->findById($id);
        $PaymentMethods->is_active = !$PaymentMethods->is_active;
        $PaymentMethods->save();
    }
    function delete($id)
    {
        $PaymentMethods = $this->findById($id);
        File::delete(public_path('uploads/PaymentMethod/' . $this->getImageName('PaymentMethod', $PaymentMethods->image)));
        $PaymentMethods->delete();
    }
}
