<?php


namespace Modules\Order\Service;

use Modules\Order\Entities\Rate;

class RateService
{

    function findAll($relation=[]){
        return Rate::with($relation)->orderByDesc('id')->get();
    }

    function findById($id,$relation=[]){
        return Rate::with($relation)->findOrFail($id);
    }

    function findBy($key, $value,$relation=[],$paginate=false)
    {
        if($paginate) return Rate::with($relation)->orderByDesc('id')->where($key, $value)->paginate($paginate);
        return Rate::with($relation)->orderByDesc('id')->where($key, $value)->get();
    }

    function save($data){
        $order =  Rate::create($data);
        return $order;
    }
}
