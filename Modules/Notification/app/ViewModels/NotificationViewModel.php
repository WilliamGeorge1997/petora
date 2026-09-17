<?php

namespace Modules\Notification\ViewModels;

use Modules\Client\Services\ClientService;
use Modules\Country\Services\CityService;

class NotificationViewModel
{
    public function cities()
    {
        return app(CityService::class)->active(columns: ['id', 'title']);
    }

    public function clients()
    {
        return app(ClientService::class)->active(columns: ['id', 'name', 'phone']);
    }
}
