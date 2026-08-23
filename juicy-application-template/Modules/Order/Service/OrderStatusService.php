<?php


namespace Modules\Order\Service;

use Illuminate\Support\Facades\File;
use Modules\Common\Helper\UploaderHelper;
use Modules\Order\Entities\OrderMethod;
use Modules\Order\Entities\OrderStatus;

class OrderStatusService
{
    use UploaderHelper;
    function findAll()
    {
        return OrderStatus::all();
    }

    function active()
    {
        return OrderStatus::active()->get();
    }

    function findById($id)
    {
        return OrderStatus::findOrFail($id);
    }

    function findBy($key, $value)
    {
        return OrderStatus::where($key, $value)->get();
    }

    function save($data)
    {
        return OrderStatus::create($data);
    }

    function update($id, $data)
    {
        $orderstatus = $this->findById($id);
        $orderstatus->update($data);
        return $orderstatus;
    }
    function delete($id)
    {
        $orderstatus = $this->findById($id);
        $orderstatus->delete();
    }
}
