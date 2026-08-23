<?php


namespace Modules\Common\Service;


use Modules\Client\Entities\Address;
use Modules\Common\Entities\Setting;

class CommonService
{

    // function findBy($key, $value)
    // {
    //     return Address::where($key, $value)->get();
    // }

    function findWhereIn($keys)
    {
        return Setting::whereIn('key', $keys)->get()->pluck('value', 'key');
    }

    function appSettings()
    {
        return Setting::where('key', 'like', 'app_%')
            ->orWhere('key', 'name')
            ->orWhere('key', 'tax_number')
            ->get();
    }

}
